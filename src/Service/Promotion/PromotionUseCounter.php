<?php

namespace App\Service\Promotion;

class PromotionUseCounter
{
    public function increase($order)
    {
        $promotion = $order->getPromotion();
        if (!is_null($promotion)) {
            $used = $promotion->getUsed();
            if (!is_null($used))
                $promotion->setUsed($used + 1);
        }
    }
}