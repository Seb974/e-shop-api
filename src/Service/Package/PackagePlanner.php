<?php

namespace App\Service\Package;

use App\Entity\OrderEntity;

class PackagePlanner
{
    public function getParcelPlan(OrderEntity $order)
    {
        $packages = $this->getPackagesArray($order);
        $items = $this->getProductsArray($order);

        foreach ($items as $item) {
            for ($i = 0; $i < count($packages); $i++) { 
                if ($item['totalWeight'] <= $packages[$i]['capacity']) {
                    $this->setInParcel($item, $packages[$i], $item['quantity']);
                    break;
                } else {
                    if (!$item['hasNoUnit'])
                        $this->tryDividingUnitsProducts($item, $packages[$i]);
                    else
                        $this->setInParcel($item, $packages[$i], $packages[$i]['capacity']);
                }
            }
        }
        return $packages;
    }

    private function setInParcel(array &$product, array &$package, float $quantity)
    {
        if ($quantity > 0) {
            $package['capacity'] -= $quantity * $product['fraction'];
            $product['quantity'] -= $quantity;
            $product['totalWeight'] -= $quantity * $product['fraction'];
            $package['cost'] += $quantity * $product['price'];
            $package['content'][] = [
                'product' => $product['product'], 
                'quantity' => $quantity, 
                'fraction' => $product['fraction'], 
                'totalWeight' => $quantity * $product['fraction']
            ];
        }
    }

    private function tryDividingUnitsProducts(array &$product, array &$package)
    {
        if ($product['quantity'] > 1) {
            for ($i = $product['quantity'] - 1; $i > 0; $i--) { 
                if ($i * $product['fraction'] <= $package['capacity']) {
                    $this->setInParcel($product, $package, $i);
                    break;
                }
            }
        }
    }

    private function getPackagesArray(OrderEntity $order)
    {
        $packagesArray = [];
        $packages = $this->sortPackages($order);
        foreach ($packages as $package) {
            $allowance = $package->getContainer()->getMax() == 3 ? 0.5 : 0;
            for ($i = 0; $i < $package->getQuantity(); $i++) {
                $container = $package->getContainer();
                $packagesArray[] = [
                    'quantity' => 1,
                    'max' => $container->getMax(),
                    'height' => $container->getHeight(),
                    'length' => $container->getLength(),
                    'width' => $container->getWidth(),
                    'capacity' => ($package->getContainer()->getMax() - $package->getContainer()->getTare() + $allowance),
                    'cost' => 0,
                    'content' => []
                ];
            }
        }
        return $packagesArray;
    }

    private function getProductsArray(OrderEntity $order)
    {
        $productsArray = [];
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            $fraction = $product->getWeight() !== null ? $product->getWeight() : 1;
            $productsArray[] = [
                'product' => $product, 
                'quantity' => $item->getOrderedQty(),
                'price' => $item->getPrice(),
                'fraction' => $fraction,
                'totalWeight' => $fraction * $item->getOrderedQty(),
                'hasNoUnit' => $item->getUnit() !== 'U',
            ];
        }
        usort($productsArray, function ($a, $b) {
            return intval($a['hasNoUnit']) - intval($b['hasNoUnit']) !== 0 ?
                   intval($a['hasNoUnit']) - intval($b['hasNoUnit']) : 
                  ($a['totalWeight'] < $b['totalWeight'] ?  1 :
                  ($a['totalWeight'] > $b['totalWeight'] ? -1 : 0 ));
        });
        return $productsArray;
    }

    private function sortPackages(OrderEntity $order)
    {
        $packages = $order->getPackages();
        if (count($packages) > 1) {
            $iterator = $packages->getIterator();
            $iterator->uasort(function($a, $b) {
                return $a->getContainer()->getMax() < $b->getContainer()->getMax() ?  1 :
                      ($a->getContainer()->getMax() > $b->getContainer()->getMax() ? -1 : 0);
            });
        }
        return $packages;
    }
}