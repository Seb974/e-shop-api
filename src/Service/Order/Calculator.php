<?php

namespace App\Service\Order;

use App\Entity\Group;
use App\Entity\Catalog;
use App\Entity\Product;
use App\Service\Tax\Tax;
use App\Entity\Container;
use App\Service\Order\Packer;
use App\Repository\CatalogRepository;
use App\Repository\ProductRepository;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;

class Calculator
{
    private $tax;
    private $packer;
    private $security;
    private $userGroupDefiner;
    private $productRepository;

    public function __construct(Security $security, UserGroupDefiner $userGroupDefiner, Packer $packer, Tax $tax, CatalogRepository $catalogRepository, ProductRepository $productRepository)
    {
        $this->tax = $tax;
        $this->packer = $packer;
        $this->security = $security;
        $this->userGroupDefiner = $userGroupDefiner;
        $this->catalogRepository = $catalogRepository;
        $this->productRepository = $productRepository;
    }

    public function getTotalCost($parameters)
    {
        $user = $this->security->getUser();
        $userGroup = $this->userGroupDefiner->getUserGroup($user);
        $catalog = $this->catalogRepository->find($parameters->get('area')['id']);
        $itemsCost = $this->getItemsCost($parameters->get('items'), $catalog, $userGroup);
        $packagesCost = $catalog->getNeedsParcel() ?
                        $this->getPackagesCost($parameters->get('items'), $catalog) : 0;

        return $itemsCost + $packagesCost;
    }

    private function getItemsCost(array $items, Catalog $catalog, Group $userGroup)
    {
        $accumulator = 0;
        foreach ($items as $item) {
            $product = $this->productRepository->find($item['product']['id']);
            $price = $this->getProductPrice($product, $catalog, $userGroup);
            $accumulator += ($item['quantity'] * $price);
        }
        return $accumulator;
    }

    private function getPackagesCost(array $items, Catalog $catalog)
    {
        $accumulator = 0;
        $packages = $this->packer->getPackages($items);
        foreach ($packages as $package) {
            $price = $this->getContainerPrice($package['container'], $catalog);
            $accumulator += ($package['quantity'] * $price);
        }
        return $accumulator;
    }

    private function getProductPrice(Product $product, Catalog $catalog, Group $userGroup)
    {
        $priceGroup = $userGroup->getPriceGroup();
        $tax = $this->tax->getTaxRate($product, $catalog);
        foreach ($product->getPrices() as $price) {
            if ($price->getPriceGroup()->getId() == $priceGroup->getId())
                return ($price->getAmount() * (1 + $tax));
        }
        return 0;
    }

    private function getContainerPrice(Container $container, Catalog $catalog)
    {
        $tax = $this->tax->getTaxRate($container, $catalog);
        foreach ($container->getCatalogPrices() as $catalogPrice) {
            if ($catalogPrice->getCatalog()->getId() == $catalog->getId())
                return ($catalogPrice->getAmount() * (1 + $tax));
        }
        return 0;
    }
}