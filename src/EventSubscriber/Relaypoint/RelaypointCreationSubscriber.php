<?php

namespace App\EventSubscriber\Relaypoint;

use App\Entity\Relaypoint;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * RelaypointCreationSubscriber
 *
 * Informations :
 * When a Relaypoint is created with POST method, this eventSusbcriber initialize its turnover and its total to pay to 0. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class RelaypointCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Relaypoint && ($method === "POST" || $method === "PUT")) {
            $this->addRelaypointsRights($result->getManagers());
        }
    }

    private function addRelaypointsRights($users)
    {
        foreach ($users as $user) {
            $originalRoles = $user->getRoles();
            $originalRoles[] = "ROLE_RELAYPOINT";
            $user->setRoles(array_unique($originalRoles));
        }
    }
}