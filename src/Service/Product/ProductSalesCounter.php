<?php

namespace App\Service\Product;

use App\Entity\OrderEntity;

class ProductSalesCounter
{
    public function increaseAll(OrderEntity $order)
    {
        foreach ($order->getItems() as $item) {
            $size = $item->getSize();
            $product = $item->getProduct();
            $product->setSaleCount($product->getSaleCount() + 1);
            if (!is_null($size)) {
                $currentQty = is_null($size->getSaleCount()) ? 0 : $size->getSaleCount();
                $size->setSaleCount($currentQty + 1);
            }
        }
    }
}