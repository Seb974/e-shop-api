<?php

namespace App\EventSubscriber\Relaypoint;

use App\Entity\User;
use App\Entity\Relaypoint;
use App\Repository\RelaypointRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsersUpdateSubscriber implements EventSubscriberInterface
{
    private $relaypointRepository;

    public function __construct(RelaypointRepository $relaypointRepository)
    {
        $this->relaypointRepository = $relaypointRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['deleteRelaypointsRights', EventPriorities::POST_READ] ];
    }

    public function deleteRelaypointsRights(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Relaypoint && $method === "PUT") {
            foreach ($previous->getManagers() as $user) {
                $this->deleteRightsIfUnique($user);
            }
        }
    }

    private function deleteRightsIfUnique(User $user)
    {
        $finalRoles = [];
        $relaypointsList = $this->relaypointRepository->findUserRelaypoints($user);
        if (count($relaypointsList) <= 1) {
            $restrictedRoles = array_diff($user->getRoles(), ["ROLE_RELAYPOINT"]);
            foreach (array_unique($restrictedRoles) as $role) {
                $finalRoles[] = $role;
            }
            $user->setRoles($finalRoles);
        }
    }
}
