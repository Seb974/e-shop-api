<?php

namespace App\EventSubscriber\Message;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\Email\MessageResponseSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * CreationSubscriber
 *
 * Informations :
 * When a message is created, this event subscriber put its 'sentAt' value.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class CreationSubscriber implements EventSubscriberInterface 
{
    private $responseSender;

    public function __construct(MessageResponseSender $responseSender)
    {
        $this->responseSender = $responseSender;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['updateEntity', EventPriorities::PRE_WRITE]
        ];
    }

    public function updateEntity(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Message) {
            if ($method === "POST") {
                $result->setSentAt(new \DateTime())
                       ->setResponse("")
                       ->setReplied(false)
                       ->setIsRead(false);
            } else if ($method === "PUT" && !$result->getReplied() && strlen($result->getResponse()) > 0) {
                $status = $this->responseSender->reply($result);
                $result->setReplied($status !== 'failed');
            }
        }
    }
}