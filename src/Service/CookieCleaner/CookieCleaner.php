<?php

namespace App\Service\CookieCleaner;

use Symfony\Component\HttpFoundation\Response;

/**
 * CookieCleaner
 *
 * Informations :
 * This service add with its unique method the convenient headers 
 * to remove the authentication cookies in browser.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class CookieCleaner
{
    private $mercurePath;
    private $cookieDomain;

    public function __construct(string $mercurePath, string $cookieDomain)
    {
        $this->mercurePath = $mercurePath;
        $this->cookieDomain = $cookieDomain;
    }

    public function addClearHeaders(Response $response) : Response
    {
        $response->headers->clearCookie('BEARER', '/', $this->cookieDomain);
        $response->headers->clearCookie('mercureAuthorization', $this->mercurePath, $this->cookieDomain);
        return $response;
    }
}