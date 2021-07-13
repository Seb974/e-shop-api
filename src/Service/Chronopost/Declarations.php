<?php

namespace App\Service\Chronopost;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\OrderEntity;
use App\Entity\Chronopost\SkybillValue;
use App\Entity\Restriction;
use App\Repository\CategoryRepository;

class Declarations
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function setContent(SkybillValue $skybill, OrderEntity &$order, array &$packagePlan)
    {
        $lines = $this->getPackageContent($order, $packagePlan);
        foreach ($lines as $key => $line) {
            $method = 'setContent'. ($key + 1);
            if (method_exists($skybill, $method))
                $skybill->$method($line);
        }
    }

    private function getPackageContent(OrderEntity $order, array &$packagePlan)
    {
        $contentArray = $this->getInitialContents();
        $declarations = $this->getPackageDeclaration($packagePlan);
        $restrictions = $this->getPackageRestrictions($order, $packagePlan);
        $content = $this->getContentDeclaration($declarations, $restrictions, $contentArray);
        return $content;
    }

    private function getContentDeclaration(array $declarations, array $restrictions, array $lines)
    {
        $i = 0;
        $announcements = array_merge($declarations, $restrictions);
        foreach ($announcements as $key => $announcement) {
            $entry = $key == count($announcements) - 1 ? $announcement : $announcement . ', ';
            if ( strlen($lines[$i]) + strlen($entry) <= 45 )
                $lines[$i] .= $entry;
            else {
                $lines[$i + 1] .= $entry;
                $i++;
            }
        }
        return $lines;
    }

    private function getPackageDeclaration(array &$packagePlan)
    {
        $declarations = [];
        foreach ($packagePlan['content'] as $item) {
            if ($item['product']->getRequireDeclaration()) {
                    $declarations[] = $item['product']->getName() . ' : ~ ' . $item['totalWeight'] . 'Kg';
            }
        }
        return $declarations;
    }

    private function getPackageRestrictions(OrderEntity $order, $package)
    {
        $restrictions = [];
        $restrictedCategories = $this->getRestrictedCategories($order);
        foreach ($restrictedCategories as $category) {
            $restriction = $this->getAssociatedRestriction($category, $order);
            $count = $this->countPackageRestrictions($package, $category, $restriction);
            if ($count > 0) {
                $unit = ' : ' . ($restriction->getUnit() === 'Kg' ? '~' : '') . $count . $restriction->getUnit();
                $restrictions[] = $category->getName() . $unit;
            }
        }
        return $restrictions;
    }

    private function countPackageRestrictions($package, $category, $restriction)
    {
        $count = 0;
        foreach ($package['content'] as $item) {
            if ($this->belongsToCategory($item['product'], $category))
                $count += $this->getQuantityToConsider($restriction, $item);
        }
        return $count;
    }

    private function getQuantityToConsider($restriction, $item)
    {
        $rUnit = $restriction->getUnit();
        $pUnit = $item['product']->getUnit();
        if ( $rUnit === $pUnit )
            return $rUnit === "U" ? $item['quantity'] : round($item['quantity'] * $item['product']->getContentWeight(), 2);
        else
            return $rUnit === "U" ? round($item['totalWeight'] / $item['fraction'], 0) : round($item['quantity'] * $item['product']->getContentWeight(), 2);
    }

    private function belongsToCategory($product, $category)
    {
        $belong = false;
        foreach ($product->getCategories() as $productCategory) {
            if ($productCategory->getId() === $category->getId()) {
                $belong = true;
                break;
            }
        }
        return $belong;
    }

    private function getInitialContents()
    {
        $content = [];
        for ($i = 0; $i < 5; $i++) {
            $content[$i] = "";
        }
        return $content;
    }

    private function getRestrictedCategories(OrderEntity $order)
    {
        return $this->categoryRepository->findRestrictedCategoriesByCatalog($order->getCatalog());
    }

    private function getAssociatedRestriction($category, OrderEntity $order)
    {
        $catalog = $order->getCatalog();
        $selection = null;
        foreach ($category->getRestrictions() as $restriction) {
            if ($restriction->getCatalog()->getId() === $catalog->getId()) {
                $selection = $restriction;
                break;
            }
        }
        return $selection;
    }
}