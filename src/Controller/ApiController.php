<?php

/**
 * ApiController
 *
 * Informations :
 * Default app controller
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
namespace App\Controller;

use App\Repository\OrderEntityRepository;
use App\Repository\PlatformRepository;
use App\Repository\ProductRepository;
use App\Repository\ProvisionRepository;
use App\Repository\StockRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/moulinette-products", name="products_stocks_link", methods={"GET"})
     *
     */
    public function updateProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        foreach ($products as $product) {
            if (!is_null($product->getStock())) {
                $product->addStock($product->getStock());
            } else {
                if (!is_null($product->getVariations() && count($product->getVariations()) > 0)) {
                    foreach ($product->getVariations() as $variation) {
                        foreach ($variation->getSizes() as $size) {
                            $size->addStock($size->getStock());
                        }
                    }
                }
            }
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($products);
    }

    /**
     * @Route("/moulinette-entities", name="entities_stocks_link", methods={"GET"})
     *
     */
    public function updateEntities(ProvisionRepository $provisionRepository, PlatformRepository $platformRepository, OrderEntityRepository $orderRepository, StockRepository $stockRepository): JsonResponse
    {
        $platform = $platformRepository->find(1);
        $stocks = $stockRepository->findAll();
        $provisions = $provisionRepository->findAll();
        $orders = $orderRepository->findAll();
        $entities = array_merge($stocks, $orders, $provisions);

        foreach ($entities as $entity) {
            $entity->setPlatform($platform);
        }
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($entities);
    }
}
