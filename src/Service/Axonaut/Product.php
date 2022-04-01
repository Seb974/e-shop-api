<?php

namespace App\Service\Axonaut;

use App\Repository\PlatformRepository;
use App\Entity\Product as ProductEntity;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Product
{
    private $domain;
    private $client;
    private $platformRepository;

    public function __construct($domain, HttpClientInterface $client, PlatformRepository $platformRepository)
    {
        $this->domain = $domain;
        $this->client = $client;
        $this->platformRepository = $platformRepository;
    }

    public function createProduct(ProductEntity $product, $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautProduct = $this->getAxonautProduct($product);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautProduct];
            $response = $this->client->request('POST', $this->domain . 'products', $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    public function updateProduct(ProductEntity $product)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautId = $product->getAccountingId();
            if (is_null($axonautId))
                return $this->createProduct($product, $platform);

            $axonautProduct = $this->getAxonautProduct($product);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautProduct];
            $response = $this->client->request('PATCH', $this->domain . 'products/' . $axonautId, $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    private function getAxonautProduct(ProductEntity $product)
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

    public function createDeliveryFormProduct()
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $DeliveryFormProduct = [
                'name' => "Bon de livraison",
                'description' => '',
                'weight' => 0,
                'product-type' => 706,
                'custom_fields' => [
                    'seller' => '-',
                    'unit' => '-',
                    'available' => 0
                ]
            ];
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $DeliveryFormProduct];
            $response = $this->client->request('POST', $this->domain . 'products', $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}