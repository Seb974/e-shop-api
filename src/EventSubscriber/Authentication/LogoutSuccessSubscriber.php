<?php

namespace App\EventSubscriber\Authentication;

use App\Service\CookieCleaner\CookieCleaner;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * LogoutSuccessSubscriber
 *
 * Informations :
 * When a user succeed on authentication, add the authentication cookies for :
 *      - JWT Authentication to API PLATFORM (BEARER)
 *      - Mercure Hub Authentication (MercureAuthorization)
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class LogoutSuccessSubscriber 
{
    private $cookieCleaner;

    public function __construct(CookieCleaner $cookieCleaner)
    {
        $this->cookieCleaner = $cookieCleaner;
    }

    public function onLogoutSuccess(LogoutEvent $event)
    {
        $response = $event->getResponse();
        return $this->cookieCleaner->addClearHeaders($response);
    }
}