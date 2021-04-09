<?php

namespace App\EventSubscriber\User;

use App\Entity\Meta;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * UserCreationSubscriber
 *
 * Informations :
 * When a user is created with POST method (register or using admin panel), 
 * this eventSusbcriber hashes the plaintext password sent and 
 * add empty metadatas (represented by Meta entity) if metas aren't sent.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class UserCreationSubscriber implements EventSubscriberInterface 
{
    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['fitDatas', EventPriorities::PRE_WRITE]
        ];
    }

    public function fitDatas(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof User && $method === "POST") {
            if ($result->getPassword() !== null) {
                $hash = $this->encoder->encodePassword($result, $result->getPassword());
                $result->setPassword($hash);
            }
            if ($result->getMetas() == null) {
                $meta = new Meta();
                $this->em->persist($meta);
                $result->setMetas($meta);
            }
        }
    }
}