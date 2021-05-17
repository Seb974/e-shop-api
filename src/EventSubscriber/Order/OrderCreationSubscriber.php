<?php

namespace App\EventSubscriber\Order;

use App\Entity\OrderEntity;
use App\Service\Order\Constructor;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreationSubscriber implements EventSubscriberInterface 
{
    private $constructor;

    public function __construct(Constructor $constructor)
    {
        $this->constructor = $constructor;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['fitOrder', EventPriorities::PRE_WRITE]
        ];
    }

    public function fitOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof OrderEntity && $method === "POST") {
            $this->constructor->adjustOrder($result);
        }
    }

}