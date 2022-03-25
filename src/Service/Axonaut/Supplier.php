<?php

namespace App\Service\Axonaut;

use App\Repository\PlatformRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Supplier
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

    public function createSupplier($entity, $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautSupplier = $this->getAxonautSupplier($entity);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautSupplier];
            $response = $this->client->request('POST', $this->domain . 'suppliers', $parameters);
            $content = $response->toArray();
            $this->updateSupplierCompany($entity, $content['company_id'], $platform);
            return $content;
        }
        return null;
    }

    public function updateSupplierCompany($supplier, $companyId, $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautSupplierCompany = $this->getAxonautSupplierCompany($supplier);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautSupplierCompany];
            $response = $this->client->request('PATCH', $this->domain . 'companies/' . $companyId, $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    public function updateSupplier($entity)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautId = $entity->getAccountingCompanyId();
            if (is_null($axonautId))
                return $this->createSupplier($entity, $platform);

            $axonautSupplier = $this->getAxonautSupplierCompany($entity);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautSupplier];
            $response = $this->client->request('PATCH', $this->domain . 'companies/' . $axonautId, $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    private function getAxonautSupplier($entity)
    {
        return [
            'name' => $entity->getName(),
            'address_contact_name' => $entity->getName()
        ];
    }

    private function getAxonautSupplierCompany($entity)
    {
        return [
            'name' => $entity->getName(),
            'internal_id' => '' . $entity->getId(),
            'address_contact_name' => $entity->getName(),
            'is_prospect' => false,
            'is_customer' => false,
            'custom_fields' => [
                'email' => $entity->getEmail(),
                'tel' => $entity->getPhone()
            ]
        ];
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}