<?php

namespace App\EventSubscriber\Lost;

use App\Entity\Lost;
use App\Service\Stock\StockManager;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * LostDeletionSubscriber
 *
 * Informations :
 * When a Lost is deleted, this eventSusbcriber send back lost quantities to stock and associated batches. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class OrderDeletionSubscriber implements EventSubscriberInterface
{
    private $stockManager;

    public function __construct(StockManager $stockManager)
    {
        $this->stockManager = $stockManager;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['sendBackStocks', EventPriorities::POST_READ] ];
    }

    public function sendBackStocks(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Lost && $method === "DELETE") {
            dump($previous);
            $this->stockManager->sendBackLostToStock($previous);
        }
    }
}
