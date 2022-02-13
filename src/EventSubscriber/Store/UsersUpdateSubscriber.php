<?php

namespace App\EventSubscriber\Store;

use App\Entity\User;
use App\Entity\Seller;
use App\Repository\StoreRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Store;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsersUpdateSubscriber implements EventSubscriberInterface
{
    private $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['deleteStoreManagersRights', EventPriorities::POST_READ] ];
    }

    public function deleteStoreManagersRights(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Store && $method === "PUT") {
            foreach ($previous->getManagers() as $user) {
                $this->deleteRightsIfUnique($user);
            }
        }
    }

    private function deleteRightsIfUnique(User $user)
    {
        $finalRoles = [];
        $storesList = $this->storeRepository->findManagerStores($user);
        if (count($storesList) <= 1) {
            $restrictedRoles = array_diff($user->getRoles(), ["ROLE_STORE_MANAGER"]);
            foreach (array_unique($restrictedRoles) as $role) {
                $finalRoles[] = $role;
            }
            $user->setRoles($finalRoles);
        }
    }
}
