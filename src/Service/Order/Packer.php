<?php

namespace App\Service\Order;

use App\Entity\OrderEntity;
use App\Entity\Package;
use App\Repository\ProductRepository;
use App\Repository\ContainerRepository;
use Doctrine\ORM\EntityManagerInterface;

class Packer
{
    private $em;
    private $containerRepository;
    private $productRepository;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository, ContainerRepository $containerRepository) 
    {
        $this->em = $em;
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

    public function setPackageEntities(OrderEntity $order)
    {
        $packages = [];
        $containers = $this->containerRepository->findBy(['available' => true], ['max'=> 'DESC']);
        $weight = $this->getProductsWeight($order);
        $minParcel = $this->getSmallestContainer($containers);
        $total = $weight;
        while ($total >= $this->getCapacity($minParcel)) {
            $container = $this->getSuitestFormat($total, $containers);
            $needs = floor($total / $this->getCapacity($container));
            $quantity = $needs > 0 ? $needs : 1;
            $this->setPackageEntity($packages, $container, $quantity);
            $rest = $total - $quantity * $this->getCapacity($container);
            $total = $rest > 0 ? $rest : 0;
        }
        if ($total > 0)
            $this->addPackageEntity($packages, $minParcel);
        
        if (count($packages) > 0)
            $this->addPackagesToOrder($packages, $order);
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

    private function setPackageEntity(&$packages, $container, $quantity)
    {
        $index = null;
        foreach ($packages as $i => $package) {
            if ($package->getContainer()->getId() === $container->getId()) {
                $index = $i;
                break;
            }
        }
        if ($index === null) {
            $package = new Package();
            $package->setContainer($container)
                    ->setQuantity($quantity);
            
            $this->em->persist($package);
            $packages[] = $package;
        } else {
            $package = $packages[$index];
            $package->setQuantity($package->getQuantity() + $quantity);
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

    private function addPackageEntity(&$packages, $minParcel)
    {
        $restAdded = false;
        foreach ($packages as $package) {
            if ($package->getContainer()->getMax() === $minParcel->getMax()) {
                $package->setQuantity($package->getQuantity() + 1);
                $restAdded = true;
                break;
            }
        }
        if (!$restAdded) {
            $package = new Package();
            $package->setContainer($minParcel)
                    ->setQuantity(1);

            $this->em->persist($package);
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

    private function getProductsWeight(OrderEntity $order)
    {
        $total = 0;
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            if (!$product->getIsMixed()) {
                $weight = $product->getUnit() === "Kg" || $product->getWeight() === null ? 1 : $product->getWeight();
                $total += ($weight * $item->getOrderedQty());
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

    private function addPackagesToOrder($packages, $order)
    {
        foreach ($packages as $package) {
            $order->addPackage($package);
        }
    }
}