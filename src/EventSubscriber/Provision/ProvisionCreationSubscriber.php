<?php

namespace App\EventSubscriber\Provision;

use App\Entity\Provision;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProvisionCreationSubscriber implements EventSubscriberInterface 
{

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitOrder', EventPriorities::PRE_WRITE] ];
    }

    public function fitOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();

        if ( $result instanceof Provision && in_array($method, ["POST", "PUT"])) {
            foreach ($result->getGoods() as $good) {
                $product = $good->getProduct();
                $product->setLastCost($good->getPrice());
            }
        }
    }
}