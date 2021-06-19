<?php

namespace App\EventSubscriber\Touring;

use App\Entity\Touring;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\Order\Constructor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderUpdateSubscriber implements EventSubscriberInterface 
{
    private $constructor;

    public function __construct(Constructor $constructor)
    {
        $this->constructor = $constructor;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['unlinkOrders', EventPriorities::PRE_WRITE] ];
    }

    public function unlinkOrders(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Touring && $method === "PUT" ) {
            foreach ($result->getOrderEntities() as $order) {
                if ($order->getStatus() === "COLLECTABLE")
                    $this->constructor->adjustDelivery($order);
            }
        }
    }
}