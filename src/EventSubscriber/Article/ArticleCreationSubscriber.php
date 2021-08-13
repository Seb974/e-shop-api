<?php

namespace App\EventSubscriber\Article;

use App\Entity\Article;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * UpdateSubscriber
 *
 * Informations :
 * When a container is created or updated, this event subscriber set the Axonaut id.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class ArticleCreationSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['addPublishDate', EventPriorities::PRE_WRITE] ];
    }

    public function addPublishDate(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Article && ($method === "POST" || $method === "PUT"))
            $result->setPublishedAt(new \DateTime());
    }
}