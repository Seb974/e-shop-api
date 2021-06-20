<?php

namespace App\EventSubscriber\Supervisor;

use App\Entity\Supervisor;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SupervisorCreationSubscriber
 *
 * Informations :
 * When a Supervisor is created with POST method, this eventSusbcriber initialize its turnover and its total to pay to 0. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class SupervisorCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Supervisor && ($method === "POST" || $method === "PUT")) {
            $this->addSupervisorRights($result->getSupervisor());
        }
    }

    private function addSupervisorRights($user)
    {
        $originalRoles = $user->getRoles();
        $originalRoles[] = "ROLE_SUPERVISOR";
        $user->setRoles(array_unique($originalRoles));
    }
}