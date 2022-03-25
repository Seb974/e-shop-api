<?php

namespace App\Service\Axonaut;

use App\Entity\Container as ContainerEntity;
use App\Repository\PlatformRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Container
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

    public function createContainer(ContainerEntity $container, $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautContainer = $this->getAxonautContainer($container);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautContainer];
            $response = $this->client->request('POST', $this->domain . 'products', $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    public function updateContainer(ContainerEntity $container)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautId = $container->getAccountingId();
            if (is_null($axonautId))
                return $this->createContainer($container, $platform);

            $axonautContainer = $this->getAxonautContainer($container);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautContainer];
            $response = $this->client->request('PATCH', $this->domain . 'products/' . $axonautId, $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
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

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}