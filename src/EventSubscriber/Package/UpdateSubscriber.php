<?php

namespace App\EventSubscriber\Package;

use App\Entity\Price;
use App\Entity\Package;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\OrderEntity;
use App\Repository\PackageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * UpdateSubscriber
 *
 * Informations :
 * When a package is updated to put returns quantities, this event subscriber dispatch the return 
 * on all packages having the same parameters.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class UpdateSubscriber implements EventSubscriberInterface 
{
    private $packageRepository;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['updateEntity', EventPriorities::PRE_WRITE]
        ];
    }

    public function updateEntity(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Package && $method === "PATCH") {
            dump("In condition");
            if ($result->getReturned() > $result->getQuantity()) {
                $this->dispatchReturns($result->getReturned(), $result->getOrderEntity()->getEmail());
            }
        }
    }

    public function dispatchReturns(int $quantity, string $email)
    {
        $concernedPackages = $this->packageRepository->findReturnablesByEmail($email);
        $rest = $quantity;
        dump($concernedPackages);
        dump($rest);
        foreach ($concernedPackages as $package) {
            if ($rest > 0) {
                $quantityReturned = $rest > $package->getQuantity() ? $package->getQuantity() : $rest;
                $package->setReturned($quantityReturned);
                $rest -= $quantityReturned;
            }
        }
    }
}