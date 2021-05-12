<?php

namespace App\Service\Stripe;

class Stripe
{
    public function __construct(string $secretKey)
    {
        \Stripe\Stripe::setApiKey($secretKey);
    }

    public function getClientSecret($amount)
    {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => (round($amount, 2) * 100),
            'currency' => 'eur'
        ]);
        return [
            'clientSecret' => $paymentIntent->client_secret,
            'amount' => $paymentIntent->amount
        ];
    }
}