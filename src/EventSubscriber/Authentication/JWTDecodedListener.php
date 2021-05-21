<?php

namespace App\EventSubscriber\Authentication;

use Symfony\Component\Security\Core\Security;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class JWTDecodedListener
{
    private $adminDomain;
    private $requestStack;
    private $security;

    public function __construct($requestStack, $admin, Security $security)
    {
        $this->adminDomain = $admin;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }

    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $origin = $request->headers->get('origin');
        $payload = $event->getPayload();
        $role = $this->getMainRole($payload['roles']);

        dump("JWT Decoded");
        dump($origin);
        dump($this->adminDomain);
        if ( !($origin == $this->adminDomain && ($this->isAdmin($role) || $this->isProvider($payload))) ) {
            dump("captured");
            $event->markAsInvalid();
        }
    }

    private function getMainRole($roles)
    {
        $filteredRoles = array_diff($roles, ["ROLE_USER"]);
        return count($filteredRoles) > 0 ? $filteredRoles[0] : "ROLE_USER";
    }

    private function isAdmin($role)
    {
        return str_contains($role, "ADMIN");
    }

    private function isProvider($user)
    {
        return $user['isSeller'] === true || $user['isDeliverer'] === true;
    }

}