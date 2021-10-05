<?php

namespace App\Service\Email;

use App\Entity\OrderEntity;
use App\Service\Email\Mailer as EmailMailer;

class OrderConfirmer
{
    private $mailer;
    private $appName;
    private $website;

    public function __construct($appName, $website, EmailMailer $mailer)
    {
        $this->mailer = $mailer;
        $this->appName = $appName;
        $this->website = $website;
    }

    public function notify(OrderEntity $order)
    {
        return $this->mailer->sendMessage(
            $order->getEmail(),
            "Récapitulatif de votre commande",
            "email/order.html.twig",
            ['order' => $order, 'name' => $this->appName, 'website' => $this->website]
        );
    }
}