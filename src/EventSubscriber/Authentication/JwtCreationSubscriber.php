<?php

namespace App\EventSubscriber\Authentication;

use App\Service\Serializer\ObjectSerializer;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

/**
 * JwtCreationSubscriber
 *
 * Informations :
 * Enhance datas contained into the JWT token with useful informations concerning the authenticated user
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class JwtCreationSubscriber {

    private $serializer;

    public function __construct(ObjectSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function updateJwtData(JWTCreatedEvent $event) 
    {
        $user = $event->getUser();
        $data = $event->getData();
        $data['id'] = $user->getId();
        $data['name'] = $user->getName();
        $data['email'] = $user->getEmail();
        $data['metas'] = $this->serializer->serializeEntity($user->getMetas(), 'users_read');

        $event->setData($data);
    }
}