<?php

namespace App\EventSubscriber\Platform;

use App\Entity\User;
use App\Entity\Platform;
use App\Repository\PlatformRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsersUpdateSubscriber implements EventSubscriberInterface
{
    private $platformRepository;

    public function __construct(PlatformRepository $platformRepository)
    {
        $this->platformRepository = $platformRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['deleteSellersRights', EventPriorities::POST_READ] ];
    }

    public function deleteSellersRights(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Platform && $method === "PUT") {
            foreach ($previous->getPickers() as $user) {
                $this->deleteRightsIfUnique($user);
            }
        }
    }

    private function deleteRightsIfUnique(User $user)
    {
        $finalRoles = [];
        $platformsList = $this->platformRepository->findUserPlatforms($user);
        if (count($platformsList) <= 1) {
            $restrictedRoles = array_diff($user->getRoles(), ["ROLE_PICKER"]);
            foreach (array_unique($restrictedRoles) as $role) {
                $finalRoles[] = $role;
            }
            $user->setRoles($finalRoles);
        }
    }
}
