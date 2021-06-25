<?php

namespace App\EventSubscriber\Provision;

use App\Entity\Provision;
use App\Service\Sms\ProvisionNotifier;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProvisionCreationSubscriber implements EventSubscriberInterface 
{

    private $provisionNotifier;

    public function __construct(ProvisionNotifier $provisionNotifier)
    {
        $this->provisionNotifier = $provisionNotifier;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitOrder', EventPriorities::PRE_WRITE] ];
    }

    public function fitOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();

        if ( $result instanceof Provision && $method === "PUT" && $result->getStatus() === "RECEIVED" ) {
            foreach ($result->getGoods() as $good) {
                $product = $good->getProduct();
                $product->setLastCost($good->getPrice());
            }
        }

        if ( $result instanceof Provision && $method === "POST" ) {
            $result->setStatus("ORDERED");
            $this->provisionNotifier->notifyOrder($result);
        }
    }
}