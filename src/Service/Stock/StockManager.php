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
            $this->manageDeliveredBatches($stock, $item);
        }
    }

    public function adjustItemPreparation($item, $order)
    {
        $stock = $this->getShopStockEntity($item, $order);
        $newQty = $stock->getQuantity() + $item->getOrderedQty() - $item->getPreparedQty();
        $stock->setQuantity($newQty);

        if (!is_null($stock->getPlatform())) {
            $allBatches = $stock->getBatches();
            foreach ($item->getTraceabilities() as $traceability) {
                $i = 0;
                $batches = $this->getBatchesWithNumber($traceability->getNumber(), $allBatches);
                $rest = $traceability->getQuantity();
                do {
                    $this->decreaseBatchesQuantities($rest, $batches, $i);
                    $i++;
                } while ($rest > 0);
            }
        }
    }

    public function addToStock($item, $provision)
    {
        $stock = $this->getShopStockEntity($item, $provision);
        $newQty = $stock->getQuantity() + $item->getReceived();
        $stock->setQuantity($newQty);
        if ($item->getProduct()->getNeedsTraceability() && !is_null($stock->getPlatform())) {
            foreach ($item->getBatches() as $batch) {
                $stock->addBatch($batch);
            }
        }
    }

    private function manageDeliveredBatches(Stock $stock, $item)
    {
        if (!is_null($stock->getPlatform())) {
            $allBatches = $stock->getBatches();
            foreach ($item->getTraceabilities() as $traceability) {
                $i = 0;
                $batches = $this->getUpdatedBatchesWithNumber($traceability, $allBatches);
                $difference = $traceability->getInitialQty() - $traceability->getQuantity();
                $rest = $difference > 0 ? $difference : -$difference;
                do {
                    if ($difference < 0)
                        $this->decreaseBatchesQuantities($rest, $batches, $i);
                    else
                        $this->increaceBatchesQuantities($rest, $batches, $i);
                    $i++;
                } while ($rest > 0 && $i <= count($batches) - 1);
            }
            $this->detachVoidBatchesFromStock($stock);
        }
    }

    private function increaceBatchesQuantities(float &$rest, &$batches, int $i)
    {
        if (!is_null($batches) && count($batches) > 0) {
            $batchQty = $batches[$i]->getQuantity();
            $batchInitialQty = $batches[$i]->getInitialQty();
            if ( $batchInitialQty > $batchQty + $rest || $i == count($batches) - 1) {
                $batches[$i]->setQuantity($batchQty + $rest);
                $rest = 0;
            } else {
                $batches[$i]->setQuantity($batchInitialQty);
                $rest -= $batchInitialQty;
            }
        }
    }

    private function decreaseBatchesQuantities(float &$rest, &$batches, int $i)
    {
        if (!is_null($batches) && count($batches) > 0 && $i <= count($batches) - 1) {
            $batchQty = $batches[$i]->getQuantity();
            if ( $rest <= $batchQty || $i == count($batches) - 1) {
                $qtyToDecrease = $batchQty - $rest > 0 ? $batchQty - $rest : 0;
                $batches[$i]->setQuantity($qtyToDecrease);
                $rest = 0;
            } else {
                $batches[$i]->setQuantity(0);
                $rest -= $batchQty;
            }
        }
    }

    private function detachVoidBatchesFromStock(Stock $stock) 
    {
        $batches = $stock->getBatches();
        foreach ($batches as $batch) {
            if ($batch->getQuantity() <= 0)
                $stock->removeBatch($batch);
        }
    }

    private function getBatchesWithNumber(string $number, $batches) 
    {
        $matchings = [];
        foreach ($batches as $batch) {
            if ($batch->getNumber() === $number)
                $matchings[] = $batch;
        }
        return $matchings;
    }

    private function getUpdatedBatchesWithNumber($traceability, $batches)
    {
        $matchings = [];
        foreach ($batches as $batch) {
            if ($batch->getNumber() === $traceability->getNumber() && $traceability->getQuantity() !== $traceability->getInitialQty())
                $matchings[] = $batch;
        }
        return $matchings;
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