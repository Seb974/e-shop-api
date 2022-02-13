<?php

namespace App\Service\Order;

use App\Entity\Group;
use App\Entity\Product;
use App\Repository\PlatformRepository;
use App\Repository\StoreRepository;
use App\Service\Tax\Tax;
use App\Service\Package\Packer;
use App\Service\Sms\OrdersNotifier;
use App\Service\Stock\StockManager;
use App\Service\Seller\SellerAccount;
use App\Service\User\UserGroupDefiner;
use App\Service\Deliverer\DelivererAccount;
use Symfony\Component\Security\Core\Security;

class Constructor
{
    private $tax;
    private $packer;
    private $security;
    private $stockManager;
    private $orderNotifier;
    private $sellerAccount;
    private $remainsCreator;
    private $userGroupDefiner;
    private $storeRepository;
    private $platformRepository;

    public function __construct(UserGroupDefiner $userGroupDefiner, Tax $tax, OrdersNotifier $orderNotifier, Security $security, RemainsCreator $remainsCreator, StockManager $stockManager, SellerAccount $sellerAccount, Packer $packer, PlatformRepository $platformRepository, StoreRepository $storeRepository)
    {
        $this->tax = $tax;
        $this->packer = $packer;
        $this->security = $security;
        $this->stockManager = $stockManager;
        $this->orderNotifier = $orderNotifier;
        $this->sellerAccount = $sellerAccount;
        $this->remainsCreator = $remainsCreator;
        $this->storeRepository = $storeRepository;
        $this->userGroupDefiner = $userGroupDefiner;
        $this->platformRepository = $platformRepository;
    }

    public function adjustOrder(&$order)
    {
        $catalog = $order->getCatalog();
        if ($catalog === null || !$catalog->getNeedsParcel() && $order->getAppliedCondition() === null) {
            throw new \Exception();
        }
        $user = $this->security->getUser();
        $platform = $this->platformRepository->find(1);
        $notification = !is_null($order->getNotification()) ? $order->getNotification() : "Email";
        $userGroup = $this->userGroupDefiner->getShopGroup($user);
        $status = $userGroup->getOnlinePayment() ? "ON_PAYMENT" : "WAITING";
        $items = $this->updateItems($order->getItems(), $catalog, $userGroup);
        $totalHT = $this->getItemsCostHT($items, 'ORDERED');
        $totalTTC = $this->getItemsCostTTC($items, 'ORDERED');
        $deliveryCostHT = $this->getDeliveryCostHT($order->getAppliedCondition(), $totalHT);
        $deliveryCostTTC = $this->getDeliveryCostTTC($order->getAppliedCondition(), $catalog, $deliveryCostHT);
        $order->setUser($user)
              ->setIsRemains(false)
              ->setRegulated(false)
              ->setInvoiced(false)
              ->setStatus($status)
              ->setNotification($notification)
              ->setTotalHT($totalHT + $deliveryCostHT)
              ->setTotalTTC($totalTTC + $deliveryCostTTC)
              ->setPlatform($platform);
        if ($catalog->getNeedsParcel()) {
            $this->packer->setPackageEntities($order);
        }
    }

    public function adjustAdminOrder(&$order)
    {
        $catalog = $order->getCatalog();
        $totalHT = $this->getItemsCostHT($order->getItems(), 'ORDERED');
        $totalTTC = $this->getItemsCostTTC($order->getItems(), 'ORDERED');
        $deliveryCostHT = $this->getDeliveryCostHT($order->getAppliedCondition(), $totalHT);
        $deliveryCostTTC = $this->getDeliveryCostTTC($order->getAppliedCondition(), $catalog, $deliveryCostHT);
        $order->setRegulated(false)
              ->setInvoiced(false)
              ->setTotalHT($totalHT + $deliveryCostHT)
              ->setTotalTTC($totalTTC + $deliveryCostTTC);
        if ($catalog->getNeedsParcel()) {
            $this->packer->setPackageEntities($order);
        }
    }

    public function adjustPreparation(&$order)
    {
        $user = $order->getUser();
        $userGroup = $this->userGroupDefiner->getShopGroup($user);
        $isPaidOnline = $userGroup->getOnlinePayment();

        if ($this->remainsCreator->hasRemains($order->getItems()))
            $this->createRemains($order, $isPaidOnline);

        $this->updateFulfilledItems($order, $isPaidOnline);

        if ( in_array($order->getStatus(), ["WAITING", "PRE-PREPARED"]) && $this->needsStatusUpdate($order) ) {
            $status = $this->getAdaptedStatus($order);
            $order->setStatus($status);
            if ( $status == 'PREPARED' && !$order->getIsRemains() && $userGroup->getSoldOutNotification()) 
                $this->orderNotifier->notifySoldOut($order);
        }
    }

    public function adjustDelivery(&$order)
    {
        $user = $order->getUser();
        $userGroup = $this->userGroupDefiner->getShopGroup($user);
        $isPaidOnline = $userGroup->getOnlinePayment();
        $isRelayPoint = $order->getMetas()->getIsRelaypoint();
        $status = $order->getStatus();

        if ($status === "COLLECTABLE" || ($status === "SHIPPED" || $status === "DELIVERED" && (is_null($isRelayPoint) || !$isRelayPoint)))
            $this->updateDeliveredOrder($order, $isPaidOnline);

        if ($status === "COLLECTABLE" && !is_null($isRelayPoint) && $isRelayPoint ) {
            $this->orderNotifier->notifyRelaypointArrivals($order);
        }
    }

    private function updateFulfilledItems(&$order, $isPaidOnline)
    {
        foreach ($order->getItems() as $item) {
            if (!is_null($item->getPreparedQty()) && !$item->getIsPrepared()) {
                $this->stockManager->adjustItemPreparation($item, $order);
                $item->setIsPrepared(true);
            }
        }

        $totalHT  = $this->getItemsCostHT($order->getItems(),  ($isPaidOnline ? 'ORDERED' : 'PREPARED'));
        $totalTTC = $this->getItemsCostTTC($order->getItems(), ($isPaidOnline ? 'ORDERED' : 'PREPARED'));
        $order->setTotalHT($totalHT)
              ->setTotalTTC($totalTTC);
    }

    private function updateDeliveredOrder(&$order, $isPaidOnline)
    {
        $this->stockManager->adjustDeliveries($order);
        foreach ($order->getItems() as $item) {
            if ( is_null($item->getDeliveredQty()) ) {
                $qtyToPay = $isPaidOnline ? $item->getOrderedQty() : $item->getPreparedQty();
                $item->setDeliveredQty($qtyToPay);
            }
        }
        $totalHT  = $this->getItemsCostHT($order->getItems(),  ($isPaidOnline ? 'ORDERED' : 'DELIVERED'));
        $totalTTC = $this->getItemsCostTTC($order->getItems(), ($isPaidOnline ? 'ORDERED' : 'DELIVERED'));
        $this->sellerAccount->dispatchTurnover($order, "INCREASE");
        $order->setTotalHT($totalHT)
              ->setTotalTTC($totalTTC)
              ->setRegulated(true);
    }

    private function needsStatusUpdate(&$order)
    {
        $isComplete = true;
        if ($order->getCatalog()->getNeedsParcel() && !$this->security->isGranted('ROLE_PICKER')) {
            $isComplete = false;
        } else {
            foreach ($order->getItems() as $item) {
                if (!$item->getIsPrepared()) {
                    $isComplete = false;
                    break;
                }
            }
        }
        return $isComplete;
    }

    private function getAdaptedStatus(&$order)
    {
        return $order->getCatalog()->getNeedsParcel() ? "READY" : (!$this->security->isGranted('ROLE_PICKER') && $this->needsRecovery($order) ? "PRE-PREPARED" : "PREPARED");
    }

    private function needsRecovery(&$order)
    {
        $needsRecovery = false;
        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getSeller()->getNeedsRecovery()) {
                $needsRecovery = true;
                break;
            }
        }
        return $needsRecovery;
    }

    private function createRemains($originalOrder, $isPaidOnline)
    {
        $remains = $this->remainsCreator->createRemains($originalOrder, $isPaidOnline);
        $totalHT = $this->getItemsCostHT($remains->getItems(), 'ORDERED');
        $totalTTC = $this->getItemsCostTTC($remains->getItems(), 'ORDERED');
        $remains->setTotalHT($totalHT)
                ->setTotalTTC($totalTTC);
    }

    private function updateItems($items, $catalog, $userGroup)
    {
        foreach ($items as $item) {
            $this->updateItem($item, $catalog, $userGroup);
        }
        return $items;
    }

    private function updateItem(&$item, $catalog, $userGroup)
    {
        $product = $item->getProduct();
        $price = $this->getProductPrice($product, $userGroup);
        $tax = $this->tax->getTaxRate($product, $catalog);
        $item->setPrice($price)
             ->setTaxRate($tax)
             ->setUnit($product->getUnit())
             ->setIsAdjourned(false)
             ->setIsPrepared(false);
    }

    private function getItemsCostHT($items, $qtyToUse)
    {
        $accumulator = 0;
        foreach ($items as $item) {
            $qty = $qtyToUse === 'ORDERED' ? $item->getOrderedQty() : 
                  ($qtyToUse === 'PREPARED' ? $item->getPreparedQty() :
                   $item->getDeliveredQty());
            $accumulator += ($qty * $item->getPrice());
        }
        return $accumulator;
    }

    private function getDeliveryCostHT($condition, $totalHT)
    {
        if ($condition == null)
            return 0;
        return $totalHT < $condition->getMinForFree() ? $condition->getPrice() : 0;
    }

    private function getDeliveryCostTTC($condition, $catalog, $deliveryCostHT)
    {
        if ($condition == null)
            return 0;

        $tax = $this->tax->getTaxRate($condition, $catalog);
        return round($deliveryCostHT * (1 + $tax) * 100) / 100;
    }

    private function getItemsCostTTC($items, $qtyToUse)
    {
        $accumulator = 0;
        foreach ($items as $item) {
            $qty = $qtyToUse === 'ORDERED' ? $item->getOrderedQty() : 
                  ($qtyToUse === 'PREPARED' ? $item->getPreparedQty() :
                   $item->getDeliveredQty());
            $accumulator += ($qty * $item->getPrice() * (1 + $item->getTaxRate()));
        }
        return $accumulator;
    }

    private function getProductPrice(Product $product, Group $userGroup)
    {
        $priceGroup = $userGroup->getPriceGroup();
        foreach ($product->getPrices() as $price) {
            if ($price->getPriceGroup()->getId() == $priceGroup->getId())
                return $price->getAmount();
        }
        return 0;
    }

    // private function getPackagesCost(array $items, Catalog $catalog)
    // {
    //     $accumulator = 0;
    //     $packages = $this->packer->getPackages($items);
    //     foreach ($packages as $package) {
    //         $price = $this->getContainerPrice($package['container'], $catalog);
    //         $accumulator += ($package['quantity'] * $price);
    //     }
    //     return $accumulator;
    // }

    // private function getContainerPrice(Container $container, Catalog $catalog)
    // {
    //     $tax = $this->tax->getTaxRate($container, $catalog);
    //     foreach ($container->getCatalogPrices() as $catalogPrice) {
    //         if ($catalogPrice->getCatalog()->getId() == $catalog->getId())
    //             return ($catalogPrice->getAmount() * (1 + $tax));
    //     }
    //     return 0;
    // }
}
