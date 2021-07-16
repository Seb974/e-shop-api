<?php

namespace App\Service\Axonaut;

use App\Entity\OrderEntity;
use App\Entity\User as UserEntity;
use App\Service\User\RolesManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class User
{
    private $key;
    private $domain;
    private $client;
    private $rolesManager;

    public function __construct($key, $domain, HttpClientInterface $client, RolesManager $rolesManager)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->client = $client;
        $this->rolesManager = $rolesManager;
    }

    public function createUser($entity)
    {
        $axonautUser = $this->getAxonautUser($entity);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautUser];
        $response = $this->client->request('POST', $this->domain . 'companies', $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    public function updateUser($user)
    {
        $axonautId = $user->getAccountingId();
        if (is_null($axonautId))
            return $this->createUser($user);

        $axonautUser = $this->getAxonautUser($user);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautUser];
        $response = $this->client->request('PATCH', $this->domain . 'companies/' . $axonautId, $parameters);
        $content = $response->toArray();
        return $content['id'];
    }

    public function removeFromCustomers($user)
    {
        $axonautId = $user->getAccountingId();
        if (is_null($axonautId))
            return ;

        $axonautUser = ['is_prospect' => true, 'is_customer' => false];
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautUser];
        $this->client->request('PATCH', $this->domain . 'companies/' . $axonautId, $parameters);
    }

    private function getAxonautUser($entity)
    {
        $metas = $entity->getMetas();
        $isCustomer = !($entity instanceof OrderEntity);
        $roles = $entity instanceof OrderEntity ? ["ROLE_USER"] : $this->rolesManager->getShopRoles($entity) ;

        return [
            'name' => $entity->getName(),
            'internal_id' => $entity instanceof OrderEntity ? '0' : '' . $entity->getId(),
            'address_contact_name' => $entity->getName(),
            'address_street' => is_null($metas) || is_null($metas->getAddress()) ? '' : $metas->getAddress(),
            'address_zip_code' => is_null($metas) || is_null($metas->getZipcode()) ? '' : $metas->getZipcode(),
            'address_city' => is_null($metas) || is_null($metas->getCity()) ? '' : $metas->getCity(),
            'categories' => $roles,
            'is_prospect' => !$isCustomer,
            'is_customer' => $isCustomer,
            'custom_fields' => [
                'email' => $entity->getEmail(),
                'tel' => is_null($metas) || is_null($metas->getPhone()) ? '' : $metas->getPhone()
            ]
        ];
    }
}