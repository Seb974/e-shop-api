<?php

namespace App\Service\Email;

use App\Entity\OrderEntity;
use App\Repository\PlatformRepository;
use App\Service\Email\Mailer as EmailMailer;

class OrderConfirmer
{
    private $mailer;
    private $website;
    private $platformRepository;

    public function __construct($website, EmailMailer $mailer, PlatformRepository $platformRepository)
    {
        $this->mailer = $mailer;
        $this->website = $website;
        $this->platformRepository = $platformRepository;
    }

    public function notify(OrderEntity $order)
    {
        $platform = $this->getPlatform();
        return $this->mailer->sendMessage(
            $order->getEmail(),
            "Récapitulatif de votre commande",
            "email/order.html.twig",
            ['order' => $order, 'name' => $platform->getName(), 'website' => $this->website]
        );
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}