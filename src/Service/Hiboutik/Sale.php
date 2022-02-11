<?php

namespace App\Service\Hiboutik;

use App\Entity\Purchase;
use App\Entity\Store;
use App\Service\Hiboutik\Request;
use App\Service\Hiboutik\Product;
use App\Entity\Sale as SaleEntity;
use App\Repository\SizeRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class Sale
{
    private $em;
    private $request;
    private $product;
    private $sizeRepository;
    private $productRepository;

    public function __construct(Request $request, Product $product, ProductRepository $productRepository, SizeRepository $sizeRepository, EntityManagerInterface $em)
    {
        $this->em =  $em;
        $this->request = $request;
        $this->product = $product;
        $this->sizeRepository = $sizeRepository;
        $this->productRepository = $productRepository;
    }

    public function getSales(Store $store)
    {
        $sale = null;
        $request = $this->getSaleRequest($store);
        $sales = $this->request->send($store, 'GET', $request);

        if (count($sales) > 0) {
            $numberOfSales = $this->getNumberOfSales($sales);
            $purchases = $this->extractProductSales($store, $sales);
            $sale = $this->createSale($store, $purchases, $numberOfSales);
        }
        return $sale;
    }

    public function getTurnover(Store $store, array $dates)
    {
        $turnover = ["CB" => 0, "ESP" => 0, "CHE"  => 0, "TOTAL" => 0];
        $period = new \DatePeriod(new \DateTime($dates['from']), new \DateInterval('P1D'), new \DateTime($dates['to']));
        foreach ($period as $key => $tDate) {
            $dayTurnover = $this->request->get($store, $store->getUrl() . '/api/z/payment_types/1/'. $tDate->format('Y') . '/' . $tDate->format('m') . '/' . $tDate->format('d'), false);
            $this->getSumupPayments($turnover, $dayTurnover);
        }
        return $turnover;
    }

    private function getNumberOfSales(array $sales)
    {
        return count(array_unique(array_column($sales, 'sale_id')));
    }

    private function getSaleRequest(Store $store)
    {
        $yDay = new \DateTime(date('d.m.Y',strtotime("-1 days")));
        return $store->getUrl() . '/api/products_sold/1/' . $yDay->format('Y') . '/' . $yDay->format('m') . '/' . $yDay->format('d');
    }

    private function extractProductSales(Store $store, array $sales)
    {
        $purchases = [];
        $products = $this->product->getHiboutikProducts($store);
        foreach ($sales as $sale) {
            $size = null;
            $hiboutikProduct = $this->getHiboutikProduct($sale["product_id"], $products);
            $product = $this->getProductEntity($hiboutikProduct);

            if ($sale["product_size"] > 0)
                $size = $this->getSizeEntity($store, $hiboutikProduct["product_size_type"], $sale["product_size"]);
            $purchases = $this->integrateSale($purchases, $sale, $product, $size);
        }
        return $purchases;
    }


    private function getHiboutikProduct(int $productId, array $hiboutikProducts)
    {
        $hiboutikProduct = [];
        foreach ($hiboutikProducts as $hiboutikProduct) {
            if ($hiboutikProduct["product_id"] == $productId)
                break;
        }
        return $hiboutikProduct;
    }

    private function getProductEntity(array $hiboutikProduct)
    {
        $productEntityId = intval($hiboutikProduct["products_ref_ext"]);
        return $this->productRepository->find($productEntityId);
    }

    private function getSizeEntity(Store $store, $variationId, $sizeId)
    {
        $sizeEntityId = 0;
        $sizes = $this->request->send($store, 'GET', $store->getUrl() . '/api/sizes/' . $variationId);
        foreach ($sizes as $size) {
            if ($size["size_id"] == $sizeId) {
                $sizeEntityId = intval($size["size_ref_ext"]);
                break;
            }
        }
        return $this->sizeRepository->find($sizeEntityId);
    }

    private function integrateSale(array $purchases, $sale, $product, $size)
    {
        $existingKey = -1;
        $newPurchase = ['product' => $product, 'size' => $size, 'price' => $sale["product_price"], 'taxRate' => $sale["vat"], 'quantity' => floatval($sale["quantity"])];
        if (!is_null($size))
            $newPurchase['variation'] = $size->getVariation();
        
        foreach ($purchases as $key => $purchase) {
            if ($purchase['product'] === $newPurchase['product'] && $purchase['size'] === $newPurchase['size'] && $purchase['price'] == $newPurchase['price']) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey == -1)
            $purchases[] = $newPurchase;
        else
            $purchases[$existingKey]['quantity'] += $newPurchase['quantity'];
        
        return $purchases;
    }

    private function createSale(Store $store, array $purchasesArray, int $numberOfSales)
    {
        $sale = $this->getNewSale($store);

        foreach ($purchasesArray as $p) {
            $purchase = $this->getPurchase($p);
            $sale->addPurchase($purchase);
        }
        $sale->setNumberOfSales($numberOfSales);
        $this->em->flush();

        return $sale;
    }

    private function getNewSale(Store $store)
    {
        $sale = new SaleEntity();
        $sale->setStore($store)
             ->setDate(new \DateTime(date('d.m.Y',strtotime("-1 days"))));
        $this->em->persist($sale);

        return $sale;
    }

    private function getPurchase($purchaseArray)
    {
        $purchase = new Purchase();
        $purchase->setProduct($purchaseArray['product'])
                 ->setPrice(floatval($purchaseArray['price']))
                 ->setTaxRate(floatval(($purchaseArray['taxRate'])))
                 ->setQuantity($purchaseArray['quantity']);

        if (array_key_exists('variation', $purchaseArray))
            $purchase->setVariation($purchaseArray['variation']);
        if (array_key_exists('size', $purchaseArray))
            $purchase->setSize($purchaseArray['size']);

        $this->em->persist($purchase);
        return $purchase;
    }

    private function getSumupPayments(array &$sumup, array $paymentTypes) {
        $total = 0;
        foreach ($paymentTypes as $paymentType) {
            $subtotal = $sumup[$paymentType['payment_type']];
            foreach ($paymentType['payments'] as $payment) {
                $subtotal += $payment['amount']; 
            }
            $sumup[$paymentType['payment_type']] = $subtotal;
            $total += $subtotal;
        }
        $sumup['TOTAL'] = $total;
    }
}