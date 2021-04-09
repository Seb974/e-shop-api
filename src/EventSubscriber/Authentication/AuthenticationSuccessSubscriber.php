<?php

namespace App\EventSubscriber\Authentication;

use App\Service\Mercure\CookieGenerator as MercureCookie;
use App\Service\JwtAuthentication\CookieGenerator as BearerCookie;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * AuthenticationSuccessSubscriber
 *
 * Informations :
 * When a user succeed on authentication, add the authentication cookies for :
 *      - JWT Authentication to API PLATFORM (BEARER)
 *      - Mercure Hub Authentication (MercureAuthorization)
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class AuthenticationSuccessSubscriber 
{
    private $bearerCookie;
    private $mercureCookie;

    public function __construct(BearerCookie $bearerCookie, MercureCookie $mercureCookie)
    {
        $this->bearerCookie = $bearerCookie;
        $this->mercureCookie = $mercureCookie;
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $response = $event->getResponse();
        $data = $event->getData();
        $user = $event->getUser();
        $response->headers->setCookie($this->bearerCookie->generate($data['token']));
        $response->headers->setCookie($this->mercureCookie->generate($user));
    }
}