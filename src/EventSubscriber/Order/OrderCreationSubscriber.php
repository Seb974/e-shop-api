<?php

namespace App\EventSubscriber\Order;

use App\Entity\OrderEntity;
use App\Service\Order\Constructor;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreationSubscriber implements EventSubscriberInterface 
{
    private $security;
    private $constructor;
    private $userGroupDefiner;

    public function __construct(Constructor $constructor, Security $security, UserGroupDefiner $userGroupDefiner)
    {
        $this->security = $security;
        $this->constructor = $constructor;
        $this->userGroupDefiner = $userGroupDefiner;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitOrder', EventPriorities::PRE_WRITE] ];
    }

    public function fitOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->security->getUser();
        $userGroup = $this->userGroupDefiner->getUserGroup($user);

        if ($result instanceof OrderEntity) {
            if ( $method === "POST" ) {
                $this->constructor->adjustOrder($result);
            } else if ( $method === "PUT" ) {
                throw new \Exception();
                $result->setStatus("WAITING");
            }
        }
    }
}