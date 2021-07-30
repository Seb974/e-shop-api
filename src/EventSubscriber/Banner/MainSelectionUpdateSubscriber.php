<?php

namespace App\EventSubscriber\Banner;

use App\Entity\Banner;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MainSelectionUpdateSubscriber
 *
 * Informations :
 * When a platform is created with POST method, this eventSusbcriber adds the adapted rights to its linked users.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class MainSelectionUpdateSubscriber implements EventSubscriberInterface 
{
    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['setUniqueSelection', EventPriorities::PRE_WRITE] ];
    }

    public function setUniqueSelection(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Banner && ($method === "POST" || $method === "PUT" || $method === "PATCH") && $result->getMain()) {
            $homepage = $result->getHomepage();
            foreach ($homepage->getBanners() as $banner) {
                if ($banner->getBannerNumber() === $result->getBannerNumber() && $banner->getMain()) {
                    $banner->setMain(false);
                }
            }
        }
    }
}