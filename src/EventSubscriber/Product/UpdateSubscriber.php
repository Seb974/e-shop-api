<?php

namespace App\EventSubscriber\Product;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Price;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * UpdateSubscriber
 *
 * Informations :
 * When a product is created or updated, this event subscriber updates its 'updatedAt' value.
 * This purpose allow Mercure to automatically send an event concerning the Product's update 
 * even when it's not the product itself that's updated but one of its dependances.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class UpdateSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['updateDateTime', EventPriorities::PRE_WRITE]
        ];
    }

    public function updateDateTime(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // if ($result instanceof Product && ($method === "POST" || $method === "PUT" || $method === "PATCH")) {
        //     $result->setUpdatedAt(new \DateTime());
        // }
    }
}