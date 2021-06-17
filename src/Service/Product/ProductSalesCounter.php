<?php

namespace App\Service\Product;

use App\Entity\OrderEntity;

class ProductSalesCounter
{
    public function increaseAll(OrderEntity $order)
    {
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            $product->setSaleCount($product->getSaleCount() + 1);
        }
    }
}