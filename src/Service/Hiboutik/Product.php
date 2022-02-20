<?php

namespace App\Service\Hiboutik;

use App\Entity\Size;
use App\Entity\Store;
use App\Entity\Stock;
use App\Entity\Variation;
use App\Service\Hiboutik\Request;
use App\Repository\PriceRepository;
use App\Entity\Product as ProductEntity;
use App\Repository\CatalogTaxRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Hiboutik\Category as HiboutikCategory;

class Product
{
    private $em;
    private $request;
    private $priceRepository;
    private $hiboutikCategory;
    private $catalogTaxRepository;

    public function __construct(Request $request, PriceRepository $priceRepository, CatalogTaxRepository $catalogTaxRepository, HiboutikCategory $hiboutikCategory, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->request = $request;
        $this->priceRepository = $priceRepository;
        $this->hiboutikCategory = $hiboutikCategory;
        $this->catalogTaxRepository = $catalogTaxRepository;
    }

    public function getHiboutikProducts(Store $store)
    {
        return $this->request->send($store, 'GET', $store->getUrl() . '/api/products');
    }

    public function getPaginatedHiboutikProducts(Store $store, int $page)
    {
        if ($page >= 1)
            return $this->request->send($store, 'GET', $store->getUrl() . '/api/products?order_by=product_model&sort=ASC&p=' . $page);
    }

    public function getHiboutikProductByProductId(Store $store, int $id) {
        return $this->request->send($store, 'GET', $store->getUrl() . '/api/products/search/?products_ref_ext=' . $id);
    }

    public function updateProductCost(Store $store, int $hiboutikId, float $cost)
    {
        $body = ["shop_id" => 1, "product_attribute" => "product_supply_price", "new_value" => $cost];
        return $this->request->send($store, 'PUT', $store->getUrl() . '/api/product/' . $hiboutikId, $body);
    }

    public function updateProductPrice(Store $store, int $hiboutikId, float $price)
    {
        $body = ["shop_id" => 1, "product_attribute" => "product_price", "new_value" => $price];
        return $this->request->send($store, 'PUT', $store->getUrl() . '/api/product/' . $hiboutikId, $body);
    }

    public function  getTaxes(Store $store)
    {
        return $this->request->send($store, 'GET', $store->getUrl() . '/api/taxes');
    }

    public function sendProducts(Store $store, array $products)
    {
        if (!is_null($store->getUrl()) && !is_null($store->getUser()) && !is_null($store->getApiKey())) {
            $categories = $this->hiboutikCategory->getHiboutikCategories($store);
            $taxes = $this->getTaxes($store);
            foreach ($products as $product) {
                $hiboutikProducts = $this->getHiboutikProduct($store, $product);
                if (count($hiboutikProducts) == 0) {
                    $this->sendToHiboutik($store, $product, $categories, $taxes);
                    $this->createStoreStocks($store, $product);
                }
            }
        }
    }

    private function sendToHiboutik(Store $store, ProductEntity $product, array $categories, array $taxes)
    {
        $categoryId = $this->getProductDepartmentId($product, $categories);
        $taxId = $this->getHiboutikTaxId($product, $taxes);
        $price = $this->getProductPrice($store, $product);
        if (!is_null($product->getVariations()) && count($product->getVariations()) > 0) {
            foreach ($product->getVariations() as $variation) {
                $variationId = $this->createVariationAndSizes($store, $variation);
                $body = $this->getFormattedVariantProduct($product, $variation, $categoryId, $variationId, $taxId, $price);
                $this->request->send($store, 'POST', $store->getUrl() . '/api/products', $body);
            }
        } else {
            $body = $this->getFormattedSimpleProduct($product, $categoryId, $taxId, $price);
            $this->request->send($store, 'POST', $store->getUrl() . '/api/products', $body);
        }
    }


    private function getHiboutikProduct(Store $store, ProductEntity $product)
    {
        return $this->request->send($store, 'GET', $store->getUrl() . '/api/products/search/?products_ref_ext=' . $product->getId());
    }

    private function getFormattedSimpleProduct(ProductEntity $product, int $categoryId, int $taxId, float $price)
    {
        return [
            "product_model" => $product->getName(),
            "product_barcode" => !is_null($product->getSku()) ? $product->getSku() : "",
            "product_price"=> $price,
            "product_category" => $categoryId,
            "product_stock_management" => 0,
            "product_size_type" => 0,
            "product_vat" => $taxId,
            "products_ref_ext" => $product->getId()
        ];
    }

    private function getFormattedVariantProduct(ProductEntity $product, Variation $variation, int $categoryId, int $variationId, int $taxId, float $price)
    {
        return [
            "product_model" => $product->getName() . " " . $variation->getColor(),
            "product_barcode" => !is_null($product->getSku()) ? $product->getSku() : "",
            "product_price"=> $price,
            "product_category" => $categoryId,
            "product_stock_management" => 0,
            "product_size_type" => $variationId,
            "product_vat" => $taxId,
            "products_ref_ext" => $product->getId()
        ];
    }

    private function getProductPrice(Store $store, ProductEntity $product)
    {
        $storeGroup = $store->getStoreGroup();
        $price = !is_null($storeGroup) ?
                    $this->priceRepository->findGroupPrice($product, $storeGroup->getValue()) : 
                    $this->priceRepository->findDefaultPrice($product);
        return !is_null($price) ? $price->getAmount() : 0;
    }

    private function getHiboutikTaxId(ProductEntity $product, array $storeTaxes)
    {
        $taxId = 0;
        $defaultTaxId = 1;
        $tax = $this->catalogTaxRepository->findLocaleTax($product);
        foreach ($storeTaxes as $storeTax) {
            if (floatval($storeTax["tax_value"]) == $tax->getPercent()) {
                $taxId = $storeTax["tax_id"];
                break;
            }
            if ($storeTax["tax_default"]) {
                $defaultTaxId = $storeTax["tax_id"];
            }
        }
        return $taxId !== 0 ? $taxId : $defaultTaxId;
    }

    private function getProductDepartmentId(ProductEntity $product, array $categories)
    {
        $id = 1;
        foreach ($categories as $category) {
            if (intval($category["category_ref_ext"]) == $product->getDepartment()->getId()) {
                $id = $category["category_id"];
                break;
            }
        }
        return $id;
    }

    private function getFormattedVariation(Variation $variation)
    {
        return [
            "size_type_name" => $variation->getColor(),
            "size_type_ref_ext" => $variation->getId()
        ];
    }

    private function getFormattedSize(Size $size, int $variationId)
    {
        return [
            "size_type_id" => $variationId,
            "size_name" => $size->getName(),
            "size_ref_ext" => $size->getId()
        ];
    }

    private function createVariationAndSizes(Store $store, $variation)
    {
        $body = $this->getFormattedVariation($variation);
        $hiboutikVariation = $this->request->send($store, 'POST', $store->getUrl() . '/api/size_types', $body);
        foreach ($variation->getSizes() as $size) {
            $body = $this->getFormattedSize($size, $hiboutikVariation["size_type_id"]);
            $this->request->send($store, 'POST', $store->getUrl() . '/api/sizes', $body);
        }
        return $hiboutikVariation["size_type_id"];
    }

    private function createStoreStocks(Store $store, ProductEntity $product)
    {
        $entityCreated = false;
        $entities = $this->getEntitiesContainingStocks($product);
        foreach ($entities as $entity) {
            if (!$this->hasExistingStock($store, $entity)) {
                $this->createNewStock($store, $entity);
                $entityCreated = true;
            }
        }

        if ($entityCreated)
            $this->em->flush();
    }

    private function getEntitiesContainingStocks(ProductEntity $product)
    {
        $entities = [];
        if (is_null($product->getVariations()))
            $entities[] = $product;
        else {
            foreach ($product->getVariations() as $variation) {
                $entities = array_merge($entities, $variation->getSizes()->toArray());
            }
        }
        return $entities;
    }

    private function createNewStock(Store $store, $entity)
    {
        $stock = New Stock();
        $stock->setStore($store)
              ->setQuantity(0)
              ->setSecurity(0)
              ->setAlert(0);

        $this->em->persist($stock);
        $entity->addStock($stock);
    }

    private function hasExistingStock(Store $store, $entity)
    {
        $exists = false;
        foreach ($entity->getStocks() as $stock) {
            if (!is_null($stock->getStore()) && $stock->getStore()->getId() == $store->getId()) {
                $exists = true;
                break;
            }
        }
        return $exists;
    }
}