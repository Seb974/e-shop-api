<?php

namespace App\Service\Axonaut;

use App\Service\Axonaut\User;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Supplier
{
    private $key;
    private $domain;
    private $client;
    private $axonautUser;

    public function __construct($key, $domain, HttpClientInterface $client, User $axonautUser)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->client = $client;
        $this->axonautUser = $axonautUser;
    }

    public function createSupplier($entity)
    {
        $axonautSupplier = $this->getAxonautSupplier($entity);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautSupplier];
        $response = $this->client->request('POST', $this->domain . 'suppliers', $parameters);
        $content = $response->toArray();
        $this->updateSupplierCompany($entity, $content['company_id']);
        return $content;
    }

    public function updateSupplierCompany($supplier, $companyId)
    {
        $axonautSupplierCompany = $this->getAxonautSupplierCompany($supplier);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautSupplierCompany];
        $response = $this->client->request('PATCH', $this->domain . 'companies/' . $companyId, $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    public function updateSupplier($entity)
    {
        $axonautId = $entity->getAccountingCompanyId();
        if (is_null($axonautId))
            return $this->createSupplier($entity);

        $axonautSupplier = $this->getAxonautSupplierCompany($entity);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautSupplier];
        $response = $this->client->request('PATCH', $this->domain . 'companies/' . $axonautId, $parameters);
        $content = $response->toArray();
        return $content['id'];
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
}