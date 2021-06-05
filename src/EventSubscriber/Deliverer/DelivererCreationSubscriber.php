<?php

namespace App\EventSubscriber\Deliverer;

use App\Entity\Deliverer;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SellerCreationSubscriber
 *
 * Informations :
 * When a deliverer is created with POST method, this eventSusbcriber initialize its turnover and its total to pay to 0. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class DelivererCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Deliverer && ($method === "POST" || $method === "PUT")) {
            if ($method === "POST") {
                $result->setTotalToPay(0);
            }
            $this->addDeliverersRights($result->getUsers());
        }
    }

    private function addDeliverersRights($users)
    {
        foreach ($users as $user) {
            $originalRoles = $user->getRoles();
            $originalRoles[] = "ROLE_DELIVERER";
            $user->setRoles(array_unique($originalRoles));
        }
    }
}