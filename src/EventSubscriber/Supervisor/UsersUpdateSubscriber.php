<?php

namespace App\EventSubscriber\Supervisor;

use App\Entity\User;
use App\Entity\Supervisor;
use App\Repository\SupervisorRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UsersUpdateSubscriber implements EventSubscriberInterface
{
    private $supervisorRepository;

    public function __construct(SupervisorRepository $supervisorRepository)
    {
        $this->supervisorRepository = $supervisorRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::REQUEST => ['deleteSupervisorsRights', EventPriorities::POST_READ] ];
    }

    public function deleteSupervisorsRights(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $previous = $event->getRequest()->attributes->get('previous_data');

        if ($previous instanceof Supervisor && $method === "PUT") {
            $user = $previous->getSupervisor();
            $this->deleteRightsIfUnique($user);
        }
    }

    private function deleteRightsIfUnique(User $user)
    {
        $finalRoles = [];
        $supervisorsList = $this->supervisorRepository->findUserSupervisors($user);
        if (count($supervisorsList) <= 1) {
            $restrictedRoles = array_diff($user->getRoles(), ["ROLE_SUPERVISOR"]);
            foreach (array_unique($restrictedRoles) as $role) {
                $finalRoles[] = $role;
            }
            $user->setRoles($finalRoles);
        }
    }
}
