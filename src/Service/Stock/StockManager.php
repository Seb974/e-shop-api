<?php

namespace App\Service\Stock;

use App\Entity\Stock;
use App\Entity\Store;
use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;

class StockManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function decreaseOrder($order)
    {
        foreach ($order->getItems() as $item) {
            $stock = $this->getShopStockEntity($item, $order);
            $newQty = $stock->getQuantity() - $item->getOrderedQty();
            $stock->setQuantity($newQty);
        }
    }

    public function decreaseSale($sale)
    {
        foreach ($sale->getPurchases() as $purchase) {
            $stock = $this->getShopStockEntity($purchase, $sale);
            if (!is_null($stock)) {
                $newQty = $stock->getQuantity() - $purchase->getQuantity();
                $stock->setQuantity($newQty);
            }
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
            if (($entity instanceof Platform && !is_null($stock->getPlatform()) && $stock->getPlatform()->getId() === $entity->getId()) || ($entity instanceof Store && !is_null($stock->getStore()) && $stock->getStore()->getId() === $entity->getId())) {
                $shopStock = $stock;
                break;
            }
        }
        return !is_null($shopStock) ? $shopStock : $this->createNewStock($entity, $item);
    }

    private function createNewStock($entity, $item)
    {
        $newStock = new Stock();
        $newStock->setProduct($item->getProduct())
                 ->setSize($item->getSize())
                 ->setAlert(0)
                 ->setSecurity(0)
                 ->setQuantity($item->getQuantity());

        if ($entity instanceof Platform)
            $newStock->setPlatform($entity);
        else if ($entity instanceof Store)
            $newStock->setStore($entity);

        $this->em->persist($newStock);
        $this->em->flush();

        return $newStock;
    }
}