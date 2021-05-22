<?php

namespace App\EventSubscriber\Authentication;

use App\Service\User\RolesManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

class JWTDecodedListener
{
    private $adminDomain;
    private $requestStack;
    private $rolesManager;

    public function __construct($requestStack, $admin, RolesManager $rolesManager)
    {
        $this->adminDomain = $admin;
        $this->rolesManager = $rolesManager;
        $this->requestStack = $requestStack;
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

        if ( !($origin == $this->adminDomain && $this->rolesManager->hasAdminAccess($payload['roles'])) ) {
            $event->markAsInvalid();
        }
    }
}