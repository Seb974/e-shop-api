<?php

namespace App\EventSubscriber\Seller;

use App\Entity\Seller;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SellerCreationSubscriber
 *
 * Informations :
 * When a seller is created with POST method, this eventSusbcriber initialize its turnover and its total to pay to 0. 
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class SellerCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE] ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Seller && ($method === "POST" || $method === "PUT")) {
            if ($method === "POST") {
                $result->setTurnover(0)
                        ->setTotalToPay(0);
            }
            $this->addSellersRights($result->getUsers());
        }
    }

    private function addSellersRights($users)
    {
        foreach ($users as $user) {
            $originalRoles = $user->getRoles();
            $originalRoles[] = "ROLE_SELLER";
            $user->setRoles(array_unique($originalRoles));
            // $user->setIsSeller(true);
        }
    }
}