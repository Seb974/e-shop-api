<?php

/**
 * HiboutikController
 *
 * Informations :
 * Controller managing communications between the website and Hiboutik app
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 * @IsGranted("ROLE_TEAM")
 */
namespace App\Controller;

use App\Entity\Store;
use App\Repository\DepartmentRepository;
use App\Repository\ProductRepository;
use App\Service\Hiboutik\Product as HiboutikProduct;
use App\Service\Hiboutik\Category as HiboutikCategory;
use App\Service\Hiboutik\Sale;
use App\Service\Request\PostRequest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HiboutikController extends AbstractController
{
    /**
     * @Route("/api/hiboutik/{id}/categories", name="hiboutik_get_categories", methods={"GET"})
     *
     */
    public function getCategories(Store $store, HiboutikCategory $hiboutik): JsonResponse
    {
        $hiboutikProducts = $hiboutik->getHiboutikCategories($store);
        return new JsonResponse($hiboutikProducts);
    }

    /**
     * @Route("/api/hiboutik/{id}/categories", name="hiboutik_send_categories", methods={"POST"})
     *
     */
    public function sendCategories(Store $store, Request $request, PostRequest $postRequest, HiboutikCategory $hiboutik, ProductRepository $productRepository, DepartmentRepository $departmentRepository): JsonResponse
    {
        $data = $postRequest->getData($request);
        if (count($data) === 0) {
            $categories = [];
            $products = is_null($store->getPlatform()) ? $productRepository->findAvailableSellerProducts($store->getSeller()) : $productRepository->findBy(["storeAvaileble" => true]);
            foreach ($products as $product) {
                if (!in_array($product->getDepartment(), $categories))
                    $categories[] = $product->getDepartment();
            }
        } else {
            $ids = $data->all();
            $categories = $departmentRepository->findBy(['id' => $ids]);
        }
        $hiboutik->sendCategories($store, $categories);
        return new JsonResponse(["status" => 200]);
    }

    /**
     * @Route("/api/hiboutik/{id}/products/{page<\d+>}", name="hiboutik_get_products", methods={"GET"})
     *
     */
    public function getProducts(Store $store, int $page = 0, HiboutikProduct $hiboutik): JsonResponse
    {
        $hiboutikProducts = $hiboutik->getHiboutikProducts($store);
        return new JsonResponse($hiboutikProducts);
    }

    /**
     * @Route("/api/hiboutik/{id}/taxes", name="hiboutik_get_taxes", methods={"GET"})
     *
     */
    public function getTaxes(Store $store, HiboutikProduct $hiboutik): JsonResponse
    {
        $hiboutikTaxes = $hiboutik->getTaxes($store);
        return new JsonResponse($hiboutikTaxes);
    }

    /**
     * @Route("/api/hiboutik/{id}/products", name="hiboutik_send_products", methods={"POST"})
     *
     */
    public function sendProducts(Store $store, Request $request, PostRequest $postRequest, HiboutikProduct $hiboutik, ProductRepository $productRepository): JsonResponse
    {
        $data = $postRequest->getData($request);
        if (count($data) === 0) {
            $products = is_null($store->getPlatform()) ? $productRepository->findAvailableSellerProducts($store->getSeller()) : $productRepository->findBy(["storeAvaileble" => true]);
        } else {
            $ids = $data->all();
            $products = $productRepository->findBy(['id' => $ids]);
        } 
        $hiboutik->sendProducts($store, $products);
        return new JsonResponse(["status" => 200]);
    }

    /**
     * @Route("/api/hiboutik/{id}/products", name="hiboutik_update_product_price", methods={"PUT"})
     *
     */
    public function updateProductPrice(Store $store, Request $request, PostRequest $postRequest, HiboutikProduct $hiboutik): JsonResponse
    {
        $data = $postRequest->getData($request);
        $product = $data->all();
        $response = $hiboutik->updateProductPrice($store, $product["hiboutikId"], $product["priceTTC"]);
        return new JsonResponse(["status" => ($response == -1 ? "fail" : "success")]);
    }

    /**
     * @Route("/api/hiboutik/{id}/sales", name="hiboutik_sales", methods={"GET"})
     *
     */
    public function getSalesPerProduct(Store $store, HiboutikProduct $hiboutik, ProductRepository $productRepository, Sale $sale): JsonResponse
    {
        $sale->getSales($store);
        return new JsonResponse(["status" => 200]);
    }

    /**
     * @Route("/api/hiboutik/{id}/turnover", name="hiboutik_turnover", methods={"POST"})
     *
     */
    public function getStoreTurnover(Store $store, Request $request, PostRequest $postRequest, Sale $sale): JsonResponse
    {
        $data = $postRequest->getData($request);
        $turnover = $sale->getTurnover($store, $data->all());
        return new JsonResponse($turnover);
    }
}