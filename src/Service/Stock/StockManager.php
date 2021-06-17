<?php

namespace App\Service\Stock;

class StockManager
{
    public function decreaseOrder($order)
    {
        foreach ($order->getItems() as $item) {
            $stock = $this->getStockEntity($item);
            $newQty = $stock->getQuantity() - $item->getOrderedQty();
            $stock->setQuantity($newQty);
        }
    }

    public function adjustPreparation($order)
    {
        foreach($order->getItems() as $item) {
            $stock = $this->getStockEntity($item);
            if ( !is_null($item->getPreparedQty()) ) {
                $newQty = $stock->getQuantity() + $item->getOrderedQty() - $item->getPreparedQty();
                $stock->setQuantity($newQty);
            }
        }
    }

    public function adjustDeliveries($order)
    {
        foreach($order->getItems() as $item) {
            $stock = $this->getStockEntity($item);
            if ( !is_null($item->getDeliveredQty()) ) {
                $newQty = $stock->getQuantity() + $item->getPreparedQty() - $item->getDeliveredQty();
                $stock->setQuantity($newQty);
            }
        }
    }

    public function adjustItemPreparation($item)
    {
        $stock = $this->getStockEntity($item);
        $newQty = $stock->getQuantity() + $item->getOrderedQty() - $item->getPreparedQty();
        $stock->setQuantity($newQty);
    }

    private function getStockEntity($item)
    {
        return !is_null($item->getSize()) ? $item->getSize()->getStock() : $item->getProduct()->getStock();
    }
}