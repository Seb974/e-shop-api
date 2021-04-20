<?php

namespace App\EventSubscriber\Group;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Group;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * GroupCreationSubscriber
 *
 * Informations :
 * When a group is created, this eventSusbcriber sets its
 * 'value' following the right way to create roles and also 
 * sets its behaviour in multiple select list (with the 
 * 'isFixed' value).
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class GroupCreationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['completeGroup', EventPriorities::PRE_WRITE]
        ];
    }

    public function completeGroup(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Group && $method === "POST") {
            $label = trim($result->getLabel());
            $value = 'ROLE_' . strtoupper(str_replace(' ', '_', $label));
            $isFixed = str_contains($value, 'ADMIN');
            $result->setLabel($label)
                   ->setValue($value)
                   ->setIsFixed($isFixed);
        }
    }
}