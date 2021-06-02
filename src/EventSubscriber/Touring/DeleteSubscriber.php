<?php

namespace App\EventSubscriber\Touring;

use App\Entity\Touring;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DeleteSubscriber implements EventSubscriberInterface 
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['unlinkOrders', EventPriorities::PRE_WRITE] ];
    }

    public function unlinkOrders(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Touring && $method === "DELETE" ) {
            foreach ($result->getOrderEntities() as $order) {
                $order->setStatus("PREPARED")
                      ->setDeliveryPriority(null);
                $result->removeOrderEntity($order);
            }
        }
    }
}