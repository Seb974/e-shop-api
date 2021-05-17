<?php

namespace App\Service\User;

use App\Repository\OrderEntityRepository;

class OrdersAnonymizer
{
    private $orderRepository;

    public function __construct(OrderEntityRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function anonymize($user)
    {
        $orders = $this->orderRepository->findBy(['user' => $user]);
        foreach ($orders as $order) {
            $order->setUser(null);
        }
    }
}