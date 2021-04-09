<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * SecurityController
 *
 * Informations :
 * Contains authentication actions
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     *
     * Informations :
     * Removes the authentication cookies on the browser
     * (BEARER and mercureAuthorization)
     */
    public function logout(): Response
    {
        throw new \Exception('Unreachable code');
    }
}
