<?php

namespace App\EventSubscriber\Deliverer;

use App\Entity\User;
use App\Entity\Deliverer;
use App\Repository\DelivererRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsersUpdateSubscriber implements EventSubscriberInterface
{
    private $delivererRepository;

    public function __construct(DelivererRepository $delivererRepository)
    {
        $this->delivererRepository = $delivererRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['deleteDeliverersRights', EventPriorities::POST_READ] ];
    }

    public function deleteDeliverersRights(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Deliverer && $method === "PUT") {
            foreach ($previous->getUsers() as $user) {
                $this->deleteRightsIfUnique($user);
            }
        }
    }

    private function deleteRightsIfUnique(User $user)
    {
        $deliverersList = $this->delivererRepository->findUserDeliverers($user);
        if (count($deliverersList) <= 1) {
            $restrictedRoles = array_diff($user->getRoles(), ["ROLE_DELIVERER"]);
            $user->setRoles(array_unique($restrictedRoles));
        }
    }
}
