<?php

namespace App\Service\Axonaut;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderEntityRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Invoice
{
    private $em;
    private $key;
    private $domain;
    private $client;
    private $orderRepository;

    public function __construct($key, $domain, HttpClientInterface $client, OrderEntityRepository $orderRepository, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->key = $key;
        $this->domain = $domain;
        $this->client = $client;
        $this->orderRepository = $orderRepository;
    }

    public function createInvoices($invoices)
    {
        $contents = [];
        foreach ($invoices as $invoice) {
            $axonautInvoice = $this->getAxonautInvoice($invoice);
            $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautInvoice];
            $response = $this->client->request('POST', $this->domain . 'invoices', $parameters);
            $content = $response->toArray();
            $contents[] = $content;
            dump($content);
        }
        return $contents;
    }

    private function getOrdersDetails($invoice)
    {
        $productsOrders = [];
        foreach ($invoice['orders'] as $id) {
            $order = $this->orderRepository->find($id);
            $consumer = $order->getUser();
            if (is_null($consumer) || $consumer->getBillingDetails() !== false) {
                $productsOrders = array_merge($productsOrders, $this->getDetailedProductsOrder($order));
            } else {
                $productsOrders = array_merge($productsOrders, $this->getSummarizedOrder($order));
            }
        }
        return $productsOrders;
    }

    private function getDetailedProductsOrder($order)
    {
        return array_merge($this->getItems($order), $this->getPackages($order));
    }

    private function getSummarizedOrder($order)
    {
        $totalOrder = $this->getItemsCost($order) + $this->getPackagesCost($order);
        return [
            'id' => 4249901,
            'name' => 'BL N°' . str_pad(strval($order->getId()), 10, "0", STR_PAD_LEFT),
            'description' => 'Du ' . ($order->getDeliveryDate())->format('d/m/Y'),
            'price' => $totalOrder,
            'tax_rate' => 0,
            'quantity' => 1,
            'description' => '',
            'discount_percent' => 0,
            'discount_flat' => 0,
        ];
    }

    private function getItems($order)
    {
        $formattedItems = [];
        foreach ($order->getItems() as $item) {
            $formattedItems[] = $this->getProduct($item, $order);
        }
        return $formattedItems;
    }

    private function getItemsCost($order)
    {
        $total = 0;
        $promotion = $order->getPromotion();
        $onlinePayment = !is_null($order->getPaymentId());
        foreach ($order->getItems() as $item) {
            $quantity = $this->getQuantity($item, $onlinePayment);
            $price = $this->getPrice($item, $order->getCatalog());
            $taxRate = $this->getTaxRate($item, $order->getCatalog());
            $itemCost = $quantity * $price * (1 + $taxRate);
            $discountedPrice = (!is_null($promotion) ? ($promotion->getPercentage() ? $itemCost * (1 - $promotion->getDiscount()) : $itemCost - round($promotion->getDiscount() / count($order->getItems()), 3)) : $itemCost);;
            $total += $discountedPrice;
        }
        return $total;
    }

    private function getPackages($order)
    {
        $formattedPackages = [];
        foreach ($order->getPackages() as $package) {
            $formattedPackages[] = $this->getProduct($package, $order);
        }
        return $formattedPackages;
    }

    private function getPackagesCost($order)
    {
        $total = 0;
        $onlinePayment = !is_null($order->getPaymentId());
        foreach ($order->getPackages() as $package) {
            $quantity = $this->getQuantity($package, $onlinePayment);
            $price = $this->getPrice($package, $order->getCatalog());
            $taxRate = $this->getTaxRate($package, $order->getCatalog());
            $total += ($quantity * $price * (1 + $taxRate));
        }
        return $total;
    }

    private function getProduct($purchase, $order)
    {
        $onlinePayment = !is_null($order->getPaymentId());
        $element = $this->getPurchaseItem($purchase);
        $promotion = $order->getPromotion();
        return [
            'id' => $element->getId(),
            'internal_id' => $element->getAccountingId(),
            'name' => $element->getName(),
            'price' => $this->getPrice($purchase, $order->getCatalog()),
            'tax_rate' => $this->getTaxRate($purchase, $order->getCatalog()),
            'quantity' => $this->getQuantity($purchase, $onlinePayment),
            'description' => '',
            'discount_percent' => $purchase instanceof Item && !is_null($promotion) && $promotion->getPercentage() ? $promotion->getDiscount() : 0,
            'discount_flat' => $purchase instanceof Item && !is_null($promotion) && !$promotion->getPercentage() ? round($promotion->getDiscount() / count($order->getItems()), 3) : 0,
        ];
    }

    private function getPurchaseItem($purchase)
    {
        return $purchase instanceof Item ? $purchase->getProduct() : $purchase->getContainer();
    }

    private function getQuantity($purchase, $onlinePayment)
    {
        if ($purchase instanceof Item) {
            $delivery = $purchase->getDeliveredQty();
            return !is_null($delivery) ? floatval($delivery) :
                    ($onlinePayment ? floatval($purchase->getOrderedQty()) : floatval($purchase->getPreparedQty()));
        } else {
            return $purchase->getQuantity();
        }
    }

    private function getPrice($purchase, $catalog)
    {
        return $purchase instanceof Item ? $purchase->getPrice() : $this->getContainerPrice($purchase, $catalog);
    }

    private function getContainerPrice($purchase, $catalog)
    {
        $selectedPrice = 0;
        $prices = $purchase->getContainer()->getCatalogPrices();
        foreach ($prices as $price) {
            if ($price->getCatalog()->getId() === $catalog->getId()) {
                $selectedPrice = $price->getAmount();
                break;
            }
        }
        return $selectedPrice;
    }

    private function getTaxRate($purchase, $catalog)
    {
        return $purchase instanceof Item ? $purchase->getTaxRate() * 100 : $this->getContainerTaxRate($purchase, $catalog);
    }

    private function getContainerTaxRate($purchase, $catalog)
    {
        $selectedTaxRate = 0;
        $taxes = $purchase->getContainer()->getTax()->getCatalogTaxes();
        foreach ($taxes as $tax) {
            if ($tax->getCatalog()->getId() === $catalog->getId()) {
                $selectedTaxRate = $tax->getPercent();
                break;
            }
        }
        return $selectedTaxRate * 100;
    }

    private function getAxonautInvoice($invoice)
    {
        return [
            'company_id' => $invoice['customer'],
            'products' => $this->getOrdersDetails($invoice),
            'delivery_address' => [
                'company_name' => '',
                'contact_name' => '',
                'street' => $invoice['metas']['address'],
                'zip_code' => $invoice['metas']['zipcode'],
                'city' => $invoice['metas']['city'],
                'region' => '',
                'country' => ''
            ],
            'allow_gocardless_payment' => true,
            'allow_stripe_payment' => true
        ];
    }

    private function updateStatuses($orders, $invoice)
    {
        $isPaid = true;
        foreach ($orders as $id) {
            $order = $this->orderRepository->find($id);
            $isPaid = is_null($order->getPaymentId()) ? false : $isPaid;
            // $order->setInvoiced(true);
        }
        // $this->em->flush();

        if ($isPaid) {
            $this->setInvoiceAsPaid($invoice);
        }
    }

    private function setInvoiceAsPaid($invoice)
    {
        $axonautPayment = [
            'invoice_id' => $invoice['id'],
            'nature' => 1,
            'amount' => $invoice['total'],
            'date' => (new \DateTime())->format('d/m/Y'),
            'reference' => ''
        ];
        dump($axonautPayment);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautPayment];
        $response = $this->client->request('POST', $this->domain . 'payments', $parameters);
        return $response->toArray();
    }

    public function updateAllStatuses($invoices, $axonautInvoices)
    {
        $payments = [];
        try {
            foreach ($invoices as $key => $invoice) {
                $payments[] = $this->updateStatuses($invoice['orders'], $axonautInvoices[$key]);
            }
        } catch(\Exception $e) {
            dump($e->getMessage());
        }
        return $payments;
    }
}