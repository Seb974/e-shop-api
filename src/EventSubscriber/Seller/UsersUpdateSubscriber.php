<?php

namespace App\EventSubscriber\Seller;

use App\Entity\User;
use App\Entity\Seller;
use App\Repository\SellerRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsersUpdateSubscriber implements EventSubscriberInterface
{
    private $sellerRepository;

    public function __construct(SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['deleteSellersRights', EventPriorities::POST_READ] ];
    }

    public function deleteSellersRights(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Seller && $method === "PUT") {
            foreach ($previous->getUsers() as $user) {
                $this->deleteRightsIfUnique($user);
            }
        }
    }

    private function deleteRightsIfUnique(User $user)
    {
        $sellersList = $this->sellerRepository->findUserSellers($user);
        if (count($sellersList) <= 1)
            $user->setIsSeller(false);
    }
}
