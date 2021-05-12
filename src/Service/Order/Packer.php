<?php

namespace App\Service\Order;

use App\Repository\ProductRepository;
use App\Repository\ContainerRepository;

class Packer
{
    private $containerRepository;
    private $productRepository;

    public function __construct(ProductRepository $productRepository, ContainerRepository $containerRepository) {
        $this->productRepository = $productRepository;
        $this->containerRepository = $containerRepository;
    }

    public function getPackages(array $items)
    {
        $packages = [];
        $containers = $this->containerRepository->findBy(['available' => true], ['max'=> 'DESC']);
        $weight = $this->getWeight($items);
        $minParcel = $this->getSmallestContainer($containers);
        $total = $weight;
        while ($total >= $this->getCapacity($minParcel)) {
            $container = $this->getSuitestFormat($total, $containers);
            $needs = floor($total / $this->getCapacity($container));
            $quantity = $needs > 0 ? $needs : 1;
            $this->setPackage($packages, $container, $quantity);

            $rest = $total - $quantity * $this->getCapacity($container);
            $total = $rest > 0 ? $rest : 0;
        }
        if ($total > 0)
            $this->addPackage($packages, $minParcel);
        return $packages;
    }

    private function setPackage(&$packages, $container, $quantity)
    {
        $index = null;
        foreach ($packages as $i => $package) {
            if ($package['container']->getId() === $container->getId()) {
                $index = $i;
                break;
            }
        }
        if ($index === null) {
            $package = ['container' => $container, 'quantity' => $quantity];
            $packages[] = $package;
        } else {
            $packages[$index]['quantity'] += $quantity;
        }
    }

    private function addPackage(&$packages, $minParcel)
    {
        $restAdded = false;
        foreach ($packages as $package) {
            $container = $package['container'];
            if ($container->getMax() === $minParcel->getMax()) {
                $package['quantity'] += 1;
                $restAdded = true;
                break;
            }
        }
        if (!$restAdded) {
            $package = ['container' => $minParcel, 'quantity' => 1];
            $packages[] = $package;
        }
    }

    private function getWeight(array $items)
    {
        $total = 0;
        foreach ($items as $item) {
            $product = $this->productRepository->find($item['product']['id']);
            if (!$product->getIsMixed()) {
                $weight = $product->getUnit() === "Kg" || $product->getWeight() === null ? 1 : $product->getWeight();
                $total += ($weight * $item['quantity']);
            }
        }
        return $total;
    }

    private function getSuitestFormat($weight, $containers)
    {
        $this->getOrderedContainers($containers);
        $last = count($containers) - 1;
        $selection = $containers[$last];
        for ( $i = 0; $i < $last; $i++ ) {
            if  ($this->isTheSuitestFormat($weight, $i, $containers)) {
                $selection = $containers[$i];
                break;
            }
        }
        return $selection;
    }

    private function isTheSuitestFormat($weight, $i, $containers)
    {
        if ($weight <= $this->getCapacity($containers[$i])) {
            return true;
        } else {
            return $i > 0 && 
                $weight > $this->getCapacity($containers[$i]) && 
                $weight < $this->getCapacity($containers[$i]) + $this->getCapacity($containers[$i - 1]);
        }
    }

    private function getCapacity($container)
    {
        return round(($container->getMax() - $container->getTare()) * 1000) / 1000;
    }

    private function getSmallestContainer($containers)
    {
        $smallest = $containers[0];
        for ( $i = 1; $i < count($containers); $i++ ) {
            $smallest = $containers[$i]->getMax() < $smallest->getMax() ? $containers[$i] : $smallest;
        }
        return $smallest;
    }

    private function getOrderedContainers(&$containers)
    {
        return usort($containers, function($a, $b) { return $a->getMax() > $b->getMax() ? 1 : -1; });
    }
}