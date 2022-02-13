<?php

namespace App\EventSubscriber\Store;

use App\Entity\Store;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * StoreCreationSubscriber
 *
 * Informations :
 * When a Store is created with POST method, this eventSusbcriber adds managers rights to users associated. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class StoreCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Store && ($method === "POST" || $method === "PUT")) {
            $this->addStoreManagersRights($result->getManagers());
        }
    }

    private function addStoreManagersRights($users)
    {
        foreach ($users as $user) {
            $originalRoles = $user->getRoles();
            $originalRoles[] = "ROLE_STORE_MANAGER";
            $user->setRoles(array_unique($originalRoles));
        }
    }
}