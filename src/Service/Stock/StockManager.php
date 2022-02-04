<?php

namespace App\Service\Stock;

use App\Entity\Platform;
use App\Entity\Store;

class StockManager
{
    public function decreaseOrder($order)
    {
        foreach ($order->getItems() as $item) {
            $stock = $this->getShopStockEntity($item, $order);
            $newQty = $stock->getQuantity() - $item->getOrderedQty();
            $stock->setQuantity($newQty);
        }
    }

    public function adjustPreparation($order)
    {
        foreach($order->getItems() as $item) {
            $stock = $this->getShopStockEntity($item, $order);
            if ( !is_null($item->getPreparedQty()) ) {
                $newQty = $stock->getQuantity() + $item->getOrderedQty() - $item->getPreparedQty();
                $stock->setQuantity($newQty);
            }
        }
    }

    public function adjustDeliveries($order)
    {
        foreach($order->getItems() as $item) {
            $stock = $this->getShopStockEntity($item, $order);
            if ( !is_null($item->getDeliveredQty()) ) {
                $newQty = $stock->getQuantity() + $item->getPreparedQty() - $item->getDeliveredQty();
                $stock->setQuantity($newQty);
            }
        }
    }

    public function adjustItemPreparation($item, $order)
    {
        $stock = $this->getShopStockEntity($item, $order);
        $newQty = $stock->getQuantity() + $item->getOrderedQty() - $item->getPreparedQty();
        $stock->setQuantity($newQty);
    }

    public function addToStock($item, $provision)
    {
        $stock = $this->getShopStockEntity($item, $provision);
        $newQty = $stock->getQuantity() + $item->getReceived();
        $stock->setQuantity($newQty);
    }

    private function getStockEntity($item)
    {
        return !is_null($item->getSize()) ? $item->getSize()->getStock() : $item->getProduct()->getStock();
    }

    private function getShopStockEntity($item, $order)
    {
        $platform = $order->getPlatform();
        return $this->getShopStock($item, is_null($platform) ? $order->getStore() : $platform);
    }

    private function getShopStock($item, $entity)
    {
        $shopStock = null;
        $stocks = !is_null($item->getSize()) && count($item->getSize()->getStocks()) > 0 ? $item->getSize()->getStocks() : $item->getProduct()->getStocks();

        foreach ($stocks as $stock) {
            if (($entity instanceof Platform && $stock->getPlatform()->getId() === $entity->getId()) || ($entity instanceof Store && $stock->getStore()->getId() === $entity->getId())) {
                $shopStock = $stock;
                break;
            }
        }
        return $shopStock;
    }
}