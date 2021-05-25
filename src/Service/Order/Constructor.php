<?php

namespace App\Service\Order;

use App\Entity\Group;
use App\Entity\Product;
use App\Service\Tax\Tax;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;

class Constructor
{
    private $tax;
    private $security;
    private $userGroupDefiner;

    public function __construct(UserGroupDefiner $userGroupDefiner, Tax $tax, Security $security)
    {
        $this->tax = $tax;
        $this->security = $security;
        $this->userGroupDefiner = $userGroupDefiner;
    }

    public function adjustOrder(&$order)
    {
        $catalog = $order->getCatalog();
        if ($catalog === null || !$catalog->getNeedsParcel() && $order->getAppliedCondition() === null) {
            throw new \Exception();
        }
        $user = $this->security->getUser();
        $userGroup = $this->userGroupDefiner->getUserGroup($user);
        $status = $userGroup->getOnlinePayment() ? "ON_PAYMENT" : "WAITING";
        $items = $this->updateItems($order->getItems(), $catalog, $userGroup);
        $totalHT = $this->getItemsCostHT($items, 'ORDERED');
        $totalTTC = $this->getItemsCostTTC($items, 'ORDERED');
        $deliveryCostHT = $this->getDeliveryCostHT($order->getAppliedCondition(), $totalHT);
        $deliveryCostTTC = $this->getDeliveryCostTTC($order->getAppliedCondition(), $catalog, $deliveryCostHT);
        $order->setUser($user)
              ->setIsRemains(false)
              ->setStatus($status)
              ->setTotalHT($totalHT + $deliveryCostHT)
              ->setTotalTTC($totalTTC + $deliveryCostTTC);
    }

    public function adjustAdminOrder(&$order)
    {
        $catalog = $order->getCatalog();
        $totalHT = $this->getItemsCostHT($order->getItems(), 'ORDERED');
        $totalTTC = $this->getItemsCostTTC($order->getItems(), 'ORDERED');
        $deliveryCostHT = $this->getDeliveryCostHT($order->getAppliedCondition(), $totalHT);
        $deliveryCostTTC = $this->getDeliveryCostTTC($order->getAppliedCondition(), $catalog, $deliveryCostHT);
        $order->setTotalHT($totalHT + $deliveryCostHT)
              ->setTotalTTC($totalTTC + $deliveryCostTTC);
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
