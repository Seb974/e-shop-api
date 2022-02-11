<?php

namespace App\EventSubscriber\Sale;

use App\Entity\Sale;
use App\Service\Stock\StockManager;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SaleCreationSubscriber implements EventSubscriberInterface
{
    private $stockManager;

    public function __construct(StockManager $stockManager)
    {
        $this->stockManager = $stockManager;
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

        if ( $result instanceof Sale && $method === "POST" ) {
            $this->stockManager->decreaseSale($result);
        }
    }
}