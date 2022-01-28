<?php

/**
 * AppiController
 *
 * Informations :
 * Default app controller
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
namespace App\Controller;

// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="api")
     * 
     * Informations :
     * Website entrypoint
     */
    public function index(): RedirectResponse
    {
        // return $this->render('base.html.twig');
        return $this->redirectToRoute('api_entrypoint');
    }

    /**
     * @Route("/test", name="hiboutik_webhook_test", methods={"POST"})
     * 
     * Informations :
     * Hiboutik Webhook test
     */
    public function test(Request $request): RedirectResponse
    {
        dump("Test OK");
        dump($request);
        return $this->redirectToRoute('api_entrypoint');
    }
}
