<?php

namespace App\EventSubscriber\Authentication;

use App\Service\Serializer\ObjectSerializer;
use App\Service\User\RolesManager;
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
    private $publicDomain;
    private $serializer;
    private $requestStack;
    private $rolesManager;

    public function __construct($requestStack, $admin, $public, ObjectSerializer $serializer, RolesManager $rolesManager)
    {
        $this->adminDomain = $admin;
        $this->publicDomain = $public;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->rolesManager = $rolesManager;
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
        $data['metas'] = $this->serializer->serializeEntity($user->getMetas(), 'users_read');
        // if ($origin == $this->adminDomain)
        //     $data['roles'] = $this->rolesManager->getAdminRoles($user);
        // else if ($origin == $this->publicDomain)
        //     $data['roles'] = $data['roles'] = $this->rolesManager->getShopRoles($user);
        // else 
            $data['roles'] = $user->getRoles();

        $event->setData($data);
    }
}