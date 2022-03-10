<?php

namespace App\EventSubscriber\Lost;

use App\Entity\Lost;
use App\Service\Stock\StockManager;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * LostCreationSubscriber
 *
 * Informations :
 * When a Lost is created with POST method, this eventSusbcriber decreases stock and batches associated. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class LostCreationSubscriber implements EventSubscriberInterface 
{
    private $stockManager;

    public function __construct(StockManager $stockManager)
    {
        $this->stockManager = $stockManager;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Lost && $method === "POST") {
            $this->stockManager->decreaseLostFromStock($result);
        }
    }
}