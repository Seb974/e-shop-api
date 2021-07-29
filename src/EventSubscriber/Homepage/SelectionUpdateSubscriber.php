<?php

namespace App\EventSubscriber\Homepage;

use App\Entity\Homepage;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\HomepageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SelectionUpdateSubscriber
 *
 * Informations :
 * When a platform is created with POST method, this eventSusbcriber adds the adapted rights to its linked users.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class SelectionUpdateSubscriber implements EventSubscriberInterface 
{
    private $homepageRepository;

    public function __construct(HomepageRepository $homepageRepository)
    {
        $this->homepageRepository = $homepageRepository;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['setUniqueSelection', EventPriorities::PRE_WRITE] ];
    }

    public function setUniqueSelection(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Homepage && ($method === "POST" || $method === "PUT" || $method === "PATCH")) {
            $homepages = $this->homepageRepository->findAll();
            if ($result->getSelected()) {
                foreach ($homepages as $homepage) {
                    if ($homepage->getId() !== $result->getId())
                        $homepage->setSelected(false);
                }
            }
        }
    }
}