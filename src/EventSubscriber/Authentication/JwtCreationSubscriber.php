<?php

namespace App\EventSubscriber\Authentication;

use App\Service\Serializer\ObjectSerializer;
use App\Service\User\UserGroupDefiner;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

/**
 * JwtCreationSubscriber
 *
 * Informations :
 * Enhance datas contained into the JWT token with useful informations concerning the authenticated user
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class JwtCreationSubscriber {

    private $adminDomain;
    private $serializer;
    private $requestStack;

    public function __construct($requestStack, $admin, ObjectSerializer $serializer)
    {
        $this->adminDomain = $admin;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
    }

    public function updateJwtData(JWTCreatedEvent $event) 
    {
        $user = $event->getUser();
        $data = $event->getData();
        $request = $this->requestStack->getCurrentRequest();
        $origin = $request->headers->get('origin');

        $data['id'] = $user->getId();
        $data['name'] = $user->getName();
        $data['email'] = $user->getEmail();
        $data['roles'] = $this->setFilteredRoles($user);
        $data['metas'] = $this->serializer->serializeEntity($user->getMetas(), 'users_read');
        if ($origin == $this->adminDomain) {
            $data['isSeller'] = $user->getIsSeller();
            $data['isDeliverer'] = $user->getIsDeliverer();
        }
        $event->setData($data);
    }

    private function setFilteredRoles($user) {
        $filteredRoles = array_diff($user->getRoles(), ["ROLE_USER"]);
        return count($filteredRoles) > 0 ? $filteredRoles : ["ROLE_USER"];
    }
}