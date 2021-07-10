<?php

namespace App\Service\Chronopost;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Chronopost\SkybillValue;

class setDeclarations
{
    private static $AUTHORIZED_PRODUCTS = ["BANANE", "COCO", "ANANAS"];
    private statIc $RESTRICTED_CATEGORIES = ["FRUIT", "LEGUME", "LÉGUME"];

    public function __construct()
    {

    }

    public function setDeclarations(array &$skybills, array &$packagesPlan)
    {
        foreach ($packagesPlan as $package) {
            $lineNumber = 1;
            $skybill = $this->getCorrespondingSkybill($skybills, $package);
            $itemsToDeclare = $this->getItemsToDeclare($package['content']);
            if (count($itemsToDeclare) > 0)
                $this->setDeliveryContent($skybill, $itemsToDeclare, $lineNumber);
        }
    }

    private function setDeliveryContent(SkybillValue $skybill, array &$itemsToDeclare, int $lineNumber)
    {
        if (count($itemsToDeclare['vegetables']) > 0) {
            foreach ($itemsToDeclare['vegetables'] as $vegetable) {
                $lineNumber = $this->makeVegetablesDeclaration($skybill, $vegetable, $lineNumber);
            }
        }
        if (count($itemsToDeclare['alcohols']) > 0)
            $this->makeAlcoholDeclaration($skybill, $itemsToDeclare['alcohols'], $lineNumber);
    }

    private function makeVegetablesDeclaration(SkybillValue $skybill, array $vegetable, int $lineNumber)
    {
        $lineDeclaration = 'content' . $lineNumber;
        $intro = $lineNumber == 1 && strlen($skybill->$lineDeclaration) == 0 ? 'Végétaux à déclarer : ' : '';
        $productDeclaration = $vegetable['product']->getName() . ': ' . (round($vegetable['weight'] * 100) / 100) . ' Kg';
        if ( (strlen($intro) == 0 && strlen($skybill->$lineDeclaration . ', ' . $productDeclaration) > 45) || (strlen($intro) > 0 && strlen($skybill->$lineDeclaration . $intro. $productDeclaration) > 45) ) {
            $skybill->$lineDeclaration .= $intro;
            $lineNumber++;
            $lineDeclaration = 'content' . $lineNumber;
            $skybill->$lineDeclaration .= $productDeclaration;
        } else {
            $skybill->$lineDeclaration .= (strlen($intro) == 0 ? ', ' . $productDeclaration : $intro . $productDeclaration);
        }
        return $lineNumber;
    }

    private function makeAlcoholDeclaration(SkybillValue $skybill, array $alcohols, int $lineNumber)
    {
        $line = $this->getAlcoholLine($skybill, $lineNumber);
        $lineDeclaration = 'content' . $line;
        $numberOfBottles = $this->getnumberOfBottles($alcohols);
        $skybill->$lineDeclaration .= (strlen($skybill->$lineDeclaration) > 0 ? ', s' : 'S') . 'piritueux : ' . $numberOfBottles . ($numberOfBottles > 1 ? ' bouteilles' : ' bouteille');
    }

    private function getnumberOfBottles(array $alcohols)
    {
        $bottles = 0;
        foreach ($alcohols as $alcohol) {
            $bottles += ($alcohol['weight'] / $alcohol['product']->getWeight());
        }
        return $bottles;
    }

    private function getAlcoholLine(SkybillValue $skybill, int $lineNumber)
    {
        $lineDeclaration = 'content' . $lineNumber;
        return strlen($skybill->$lineDeclaration) > 0 && $lineNumber < 4 ? $lineNumber + 1 : $lineNumber;
    }

    private function getItemsToDeclare(array &$items)
    {
        $itemsToDeclare = ['vegetables' => [], 'alcohols' => []];
        foreach ($items as $item) {
            if ( $this->isAVegetableToDeclare($item['product'], $item['totalWeight']) )
                $itemsToDeclare['vegetables'][] = ['product' => $item['product'],'weight' => $item['totalWeight']];
            else if ( $this->isAnAlcoholToDeclare($item['product']) )
                $itemsToDeclare['alcohols'][] = ['product' => $item['product'],'weight' => $item['totalWeight']];
        }
        return $itemsToDeclare;
    }

    private function getCorrespondingSkybill(array &$skybills, $package)
    {
        $selection = $skybills[0];
        if (count($skybills) > 1) {
            foreach ($skybills as $skybill) {
                if (strlen($skybill->content1) == 0 && $skybill->height == $package['height'] && $skybill->length == $package['length'] && $skybill->width == $package['width']) {
                    $selection = $skybill;
                    break;
                }
            }
        }
        return $selection;
    }

    private function isAVegetableToDeclare(Product $product, float $weight)
    {
        if ( $this->hasRestrictions($product) ) {
            foreach ($product->getCategories() as $category) {
                if ($this->needsDeclaration($weight, $category))
                    return true;
            }
        }
        return false;
    }

    private function isAnAlcoholToDeclare(Product $product)
    {
        return $product->getRequireLegalAge();
    }

    private function hasRestrictions(Product $product)
    {
        foreach (self::$AUTHORIZED_PRODUCTS as $productName) {
            if ( strpos(trim(strtoupper($product->getName())), $productName) !== false ) {
                return false;
            }
        }
        return true;
    }

    private function needsDeclaration(float $weight, Category $category)
    {
        foreach (self::$RESTRICTED_CATEGORIES as $categoryName) {
            if ( strpos(trim(strtoupper($category->getName())), $categoryName) !== false && $weight > 5 ) {
                return true;
            }
        }
        return false;
    }
}