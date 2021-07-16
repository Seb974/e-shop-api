<?php

namespace App\Service\Axonaut;

use App\Entity\Container as ContainerEntity;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Container
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

    public function createContainer(ContainerEntity $container)
    {
        $axonautContainer = $this->getAxonautContainer($container);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautContainer];
        $response = $this->client->request('POST', $this->domain . 'products', $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    public function updateContainer(ContainerEntity $container)
    {
        $axonautId = $container->getAccountingId();
        if (is_null($axonautId))
            return $this->createContainer($container);

        $axonautContainer = $this->getAxonautContainer($container);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautContainer];
        $response = $this->client->request('PATCH', $this->domain . 'products/' . $axonautId, $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    private function getAxonautContainer(ContainerEntity $container)
    {
        return [
            'name' => $container->getName(),
            'description' => 'Contenance : ' . round($container->getMax() - $container->getTare(), 3) . 'Kg',
            'internal_id' => $container->getId(),
            'weight' => $container->getMax(),
            'product-type' => 701,
            'custom_fields' => [
                'seller' => '-',
                'unit' => 'U',
                'available' => $container->getAvailable()
            ]
        ];
    }
}