<?php

namespace App\Service\Axonaut;

use App\Entity\Item;
use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderEntityRepository;
use App\Repository\PlatformRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Invoice
{
    private $em;
    private $domain;
    private $client;
    private $orderRepository;
    private $platformRepository;

    public function __construct($domain, HttpClientInterface $client, OrderEntityRepository $orderRepository, EntityManagerInterface $em, PlatformRepository $platformRepository)
    {
        $this->em = $em;
        $this->domain = $domain;
        $this->client = $client;
        $this->orderRepository = $orderRepository;
        $this->platformRepository = $platformRepository;
    }

    public function getAllInvoices($from, $to)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            try {
                $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()]];
                $response = $this->client->request('GET', $this->domain . 'invoices?date_before=' . $to->format('d/m/Y') . '&date_after=' . $from->format('d/m/Y') , $parameters);
                return $response->toArray();
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getInvoicesForUser($userId, $from, $to)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            try {
                $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()]];
                $response = $this->client->request('GET', $this->domain . 'companies/' . $userId . '/invoices?updated_before=' . $to->format('d/m/Y') . '&updated_after=' . $from->format('d/m/Y'), $parameters);
                return $response->toArray();
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function createInvoices($invoices)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $contents = [];
            foreach ($invoices as $invoice) {
                $axonautInvoice = $this->getAxonautInvoice($invoice);
                $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautInvoice];
                $response = $this->client->request('POST', $this->domain . 'invoices', $parameters);
                $content = $response->toArray();
                $contents[] = $content;
                $this->updateStatuses($invoice['orders'], $content, $platform);
            }
            return $contents;
        }
        return null;
    }

    public function createPayment($invoices, $paymentId)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            try {
                foreach ($invoices as $invoice) {
                    $this->setInvoiceAsPaid($invoice, $platform);
                    $orders = $this->orderRepository->findBy(['invoiceId' => $invoice['id']]);
                    foreach ($orders as $order) {
                        $order->setPaymentId($paymentId);
                    }
                    $this->em->flush();
                }
                return ['data' => true];
            } catch(\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }
        return null;
    }

    private function getOrdersDetails($invoice)
    {
        $productsOrders = [];
        foreach ($invoice['orders'] as $id) {
            $order = $this->orderRepository->find($id);
            $consumer = $order->getUser();
            if (is_null($consumer) || !$consumer->getBillingDetails()) {
                $productsOrders = array_merge($productsOrders, $this->getDetailedProductsOrder($order));
            } else {
                $productsOrders[] = $this->getSummarizedOrder($order);
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
        $chapter = $this->getChapter($order);
        $taxes = $this->getTaxesRates($order);
        foreach ($taxes as $taxRate) {
            $totalOrder = $this->getItemsCost($order, $taxRate) + $this->getPackagesCost($order, $taxRate);
            return [
                'id' => 4249901,
                'name' => 'Total produits à TVA ' . number_format((float)$taxRate, 2, ',', ' ') . '%',
                'description' => '',
                'price' => $totalOrder,
                'tax_rate' => $taxRate,
                'quantity' => 1,
                'description' => '',
                'chapter' => $chapter,
                'discount_percent' => 0,
                'discount_flat' => 0,
            ];
        }
    }

    private function getItems($order)
    {
        $formattedItems = [];
        foreach ($order->getItems() as $item) {
            $formattedItems[] = $this->getProduct($item, $order);
        }
        return $formattedItems;
    }

    private function getItemsCost($order, $taxRateSearched)
    {
        $total = 0;
        $promotion = $order->getPromotion();
        $onlinePayment = !is_null($order->getPaymentId());
        foreach ($order->getItems() as $item) {
            $taxRate = $this->getTaxRate($item, $order->getCatalog());
            if ($taxRate === $taxRateSearched) {
                $quantity = $this->getQuantity($item, $onlinePayment);
                $price = $this->getPrice($item, $order->getCatalog());
                $itemCost = $quantity * $price;
                $discountedPrice = (!is_null($promotion) ? ($promotion->getPercentage() ? $itemCost * (1 - $promotion->getDiscount()) : $itemCost - round($promotion->getDiscount() / count($order->getItems()), 3)) : $itemCost);;
                $total += $discountedPrice;
            }
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

    private function getPackagesCost($order, $taxRateSearched)
    {
        $total = 0;
        $onlinePayment = !is_null($order->getPaymentId());
        foreach ($order->getPackages() as $package) {
            $taxRate = $this->getTaxRate($package, $order->getCatalog());
            if ($taxRate === $taxRateSearched) {
                $quantity = $this->getQuantity($package, $onlinePayment);
                $price = $this->getPrice($package, $order->getCatalog());
                $total += ($quantity * $price);
            }
        }
        return $total;
    }

    private function getProduct($purchase, $order)
    {
        $onlinePayment = !is_null($order->getPaymentId());
        $element = $this->getPurchaseItem($purchase);
        $promotion = $order->getPromotion();
        $chapter = $this->getChapter($order);
        $quantity = $this->getQuantity($purchase, $onlinePayment);

        return $quantity <= 0 ? [] : [
            'id' => $element->getId(),
            'internal_id' => $element->getAccountingId(),
            'name' => $element->getName(),
            'price' => $this->getPrice($purchase, $order->getCatalog()),
            'tax_rate' => $this->getTaxRate($purchase, $order->getCatalog()),
            'quantity' => $this->getQuantity($purchase, $onlinePayment),
            'description' => '',
            'chapter' => $chapter,
            'discount_percent' => $purchase instanceof Item && !is_null($promotion) && $promotion->getPercentage() ? $promotion->getDiscount() : 0,
            'discount_flat' => $purchase instanceof Item && !is_null($promotion) && !$promotion->getPercentage() ? round($promotion->getDiscount() / count($order->getItems()), 3) : 0,
        ];
    }

    private function getChapter($order)
    {
        return 'BL N°' . str_pad(strval($order->getId()), 10, "0", STR_PAD_LEFT);
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
        return $this->getEntityTaxRate($purchase, $catalog);
    }

    private function getTaxesRates($order)
    {
        $taxes = [];
        $purchases = array_merge($order->getItems()->toArray(), $order->getPackages()->toArray());
        foreach ($purchases as $purchase) {
            $taxes[] = $this->getEntityTaxRate($purchase, $order->getCatalog());
        }
        return array_unique($taxes);
    }

    private function getEntityTaxRate($purchase, $catalog)
    {
        $selectedTaxRate = 0;
        $entity = $purchase instanceof Item ? $purchase->getProduct() : $purchase->getContainer();
        $taxes = $entity->getTax()->getCatalogTaxes();
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

    private function updateStatuses($orders, $invoice, Platform $platform)
    {
        $isPaid = true;
        foreach ($orders as $id) {
            $order = $this->orderRepository->find($id);
            $isPaid = is_null($order->getPaymentId()) ? false : $isPaid;
            $order->setInvoiced(true)
                  ->setInvoiceId($invoice['id']);
        }
        $this->em->flush();

        if ($isPaid) {
            try {
                $this->setInvoiceAsPaid($invoice, $platform);
            } catch(\Exception $e) {
                dump($e->getMessage());
            }
        }
    }

    private function setInvoiceAsPaid($invoice, Platform $platform)
    {
        $axonautPayment = [
            'invoice_id' => $invoice['id'],
            'nature' => 4,
            'amount' => $invoice['total'],
            'date' => (new \DateTime())->format(\DateTime::RFC3339),
            'reference' => ''
        ];
        $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautPayment];
        $response = $this->client->request('POST', $this->domain . 'payments', $parameters);
        return $response->toArray();
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}