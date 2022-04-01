<?php

namespace App\Service\Axonaut;

use App\Entity\OrderEntity;
use App\Entity\User as UserEntity;
use App\Repository\PlatformRepository;
use App\Service\User\RolesManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class User
{
    private $domain;
    private $client;
    private $rolesManager;
    private $platformRepository;

    public function __construct($domain, HttpClientInterface $client, RolesManager $rolesManager, PlatformRepository $platformRepository)
    {
        $this->domain = $domain;
        $this->client = $client;
        $this->rolesManager = $rolesManager;
        $this->platformRepository = $platformRepository;
    }

    public function createUser($entity, $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautUser = $this->getAxonautUser($entity);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautUser];
            $response = $this->client->request('POST', $this->domain . 'companies', $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    public function updateUser($user)
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            $axonautId = $user->getAccountingId();
            if (is_null($axonautId))
                return $this->createUser($user, $platform);

            $axonautUser = $this->getAxonautUser($user);
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautUser];
            $response = $this->client->request('PATCH', $this->domain . 'companies/' . $axonautId, $parameters);
            $content = $response->toArray();
            return $content['id'];
        }
        return null;
    }

    public function removeFromCustomers($user)
    {
        $platform = $this->getPlatform();
        $axonautId = $user->getAccountingId();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey()) && !is_null($axonautId)) {
            $axonautUser = ['is_prospect' => true, 'is_customer' => false];
            $parameters = [ 'headers' => ['userApiKey' => $platform->getAxonautKey()], 'body' => $axonautUser];
            $this->client->request('PATCH', $this->domain . 'companies/' . $axonautId, $parameters);
        }
        return ;
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

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}