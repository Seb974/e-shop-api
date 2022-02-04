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

use App\Repository\OrderEntityRepository;
use App\Repository\PlatformRepository;
use App\Repository\ProvisionRepository;
use App\Repository\StockRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        return $this->redirectToRoute('api_entrypoint');
    }

    /**
     * @Route("/moulinette-stocks", name="stocks_link", methods={"GET"})
     *
     */
    public function updateProvisions(ProvisionRepository $provisionRepository, PlatformRepository $platformRepository): JsonResponse
    {
        $platform = $platformRepository->find(1);
        $provisions = $provisionRepository->findAll();
        foreach ($provisions as $provision) {
            $provision->setPlatform($platform);
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($provisions);
    }
}
