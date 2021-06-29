<?php

namespace App\EventSubscriber\Touring;

use App\Entity\Touring;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\Deliverer\DelivererAccount;
use App\Service\Order\Constructor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderUpdateSubscriber implements EventSubscriberInterface 
{
    private $constructor;
    private $delivererAccount;

    public function __construct(Constructor $constructor, DelivererAccount $delivererAccount)
    {
        $this->constructor = $constructor;
        $this->delivererAccount = $delivererAccount;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['unlinkOrders', EventPriorities::PRE_WRITE] ];
    }

    public function unlinkOrders(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Touring) {
            if ($method === "PUT" && !$result->getRegulated()) {
                foreach ($result->getOrderEntities() as $key => $order) {
                    $isRelayPoint = $order->getMetas()->getIsRelaypoint();
                    $status = $order->getStatus();
                    if ($status === "COLLECTABLE" || ($status === "DELIVERED" && (is_null($isRelayPoint) || !$isRelayPoint)) ) {
                        $this->constructor->adjustDelivery($order);
                        if ($key == count($result->getOrderEntities()) - 1)
                            $this->delivererAccount->dispatchTurnover($result, "INCREASE");
                    }
                }
            } else if ($method === "PUT" && $result->getRegulated()) {
                $this->delivererAccount->dispatchTurnover($result, "DECREASE");
            } else if ($method === "POST") {
                $result->setRegulated(false);
            }
        }
    }
}