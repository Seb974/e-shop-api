<?php

namespace App\EventSubscriber\Platform;

use App\Entity\Platform;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * PlatformCreationSubscriber
 *
 * Informations :
 * When a platform is created with POST method, this eventSusbcriber adds the adapted rights to its linked users.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class PlatformCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Platform && ($method === "POST" || $method === "PUT")) {
            $this->addSellersRights($result->getPickers());
        }
    }

    private function addSellersRights($users)
    {
        foreach ($users as $user) {
            $originalRoles = $user->getRoles();
            $originalRoles[] = "ROLE_PICKER";
            $user->setRoles(array_unique($originalRoles));
        }
    }
}