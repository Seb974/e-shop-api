<?php

namespace App\Service\User;

use App\Entity\OrderEntity;

class UserOrderCounter
{
    public function increase(OrderEntity $order)
    {
        $user = $order->getUser();
        if (!is_null($user)) {
            $user->setOrderCount($user->getOrderCount() + 1)
                 ->setLastOrder(new \DateTime());
        }
    }
}