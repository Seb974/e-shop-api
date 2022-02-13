<?php

namespace App\Service\Order;

use App\Entity\Item;
use App\Entity\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;

class RemainsCreator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function hasRemains($items)
    {
        $hasRemains = false;
        foreach ($items as $item) {
            if ($item->getIsAdjourned()) {
                $hasRemains = true;
                break;
            }
        }
        return $hasRemains;
    }

    public function createRemains($originalOrder, $isPaidOnline)
    {
        $remains = $this->createRemainsEntity($originalOrder);
        return $this->setRemainsItems($originalOrder, $remains, $isPaidOnline);
    }

    private function createRemainsEntity($originalOrder)
    {
        $remains = new OrderEntity();
        $remains->setName($originalOrder->getName())
                ->setEmail($originalOrder->getEmail())
                ->setMetas($originalOrder->getMetas())
                ->setUser($originalOrder->getUser())
                ->setMessage($originalOrder->getMessage())
                ->setCatalog($originalOrder->getCatalog())
                ->setPromotion($originalOrder->getPromotion())
                ->setPaymentId($originalOrder->getPaymentId())
                ->setPaymentId($originalOrder->getPaymentId())
                ->setDeliveryDate($originalOrder->getDeliveryDate())
                ->setAppliedCondition($originalOrder->getAppliedCondition())
                ->setUuid($originalOrder->getUuid())
                ->setStatus("WAITING")
                ->setIsRemains(true);
        if (!is_null($originalOrder->getPlatform())) {
            $remains->setPlatform($originalOrder->getPlatform());
        } else if (!is_null($originalOrder->getPStore())) {
            $remains->setStore($originalOrder->getStore());
        }
        $this->em->persist($remains);
        return $remains;
    }

    private function setRemainsItems($originalOrder, $remains, $isPaidOnline)
    {
        foreach ($originalOrder->getItems() as $originalItem) {
            if ($originalItem->getIsAdjourned()) {
                $rest = $originalItem->getOrderedQty() - $originalItem->getPreparedQty();
                $item = new Item();
                $item->setProduct($originalItem->getProduct())
                     ->setVariation($originalItem->getVariation())
                     ->setSize($originalItem->getSize())
                     ->setUnit($originalItem->getUnit())
                     ->setPrice(!$isPaidOnline ? $originalItem->getPrice() : 0)
                     ->setTaxRate($originalItem->getTaxRate())
                     ->setOrderedQty($rest)
                     ->setIsAdjourned(false)
                     ->setIsPrepared(false);
                $this->em->persist($item);
                $remains->addItem($item);
            }
        }
        return $remains;
    }
}