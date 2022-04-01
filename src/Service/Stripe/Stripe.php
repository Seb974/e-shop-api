<?php

namespace App\Service\Stripe;

use App\Repository\PlatformRepository;

class Stripe
{
    private $platform;

    public function __construct(PlatformRepository $platformRepository)     // string $secretKey, 
    {
        $this->platform = $platformRepository->find(1);
        \Stripe\Stripe::setApiKey($this->platform->getStripePrivateKey());
    }

    public function getClientSecret($amount)
    {
        if (!is_null($this->platform->getStripePrivateKey())) {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (round($amount, 2) * 100),
                'currency' => 'eur'
            ]);
            return [
                'clientSecret' => $paymentIntent->client_secret,
                'amount' => $paymentIntent->amount
            ];
        }
        return null;
    }
}