<?php

namespace App\Service\Axonaut;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\OrderEntity;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AxonautProduct
{
    private $key;
    private $domain;
    private $client;

    public function __construct($key, $domain, HttpClientInterface $client)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->client = $client;
    }

    public function createProduct(Product $product)
    {
        $axonautProduct = $this->getAxonautProduct($product);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautProduct];
        $response = $this->client->request('POST', $this->domain . 'products', $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    public function updateProduct($product)
    {
        $axonautId = $product->getAccountingId();
        if (is_null($axonautId))
            return $this->createProduct($product);

        $axonautProduct = $this->getAxonautProduct($product);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautProduct];
        $response = $this->client->request('PATCH', $this->domain . 'products/' . $axonautId, $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    private function getAxonautProduct($product)
    {
        return [
            'name' => $product->getName(),
            'description' => $product->getFullDescription(),
            'internal_id' => $product->getId(),
            'weight' => $product->getWeight(),
            'product-type' => 701,
            'custom_fields' => [
                'seller' => $product->getSeller()->getName(),
                'unit' => $product->getUnit(),
                'available' => $product->getAvailable()
            ]
        ];
    }
}