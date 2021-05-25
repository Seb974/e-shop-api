<?php

namespace App\EventSubscriber\Order;

use App\Entity\OrderEntity;
use App\Service\Order\Constructor;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreationSubscriber implements EventSubscriberInterface 
{
    private $security;
    private $constructor;
    private $userGroupDefiner;
    private $adminDomain;
    private $publicDomain;

    public function __construct($admin, $public, Constructor $constructor, Security $security, UserGroupDefiner $userGroupDefiner)
    {
        $this->adminDomain = $admin;
        $this->security = $security;
        $this->publicDomain = $public;
        $this->constructor = $constructor;
        $this->userGroupDefiner = $userGroupDefiner;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitOrder', EventPriorities::PRE_WRITE] ];
    }

    public function fitOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();
        $origin = $request->headers->get('origin');
        $user = $this->security->getUser();
        $userGroup = $this->userGroupDefiner->getUserGroup($user);

        if ($result instanceof OrderEntity) {
            if ($origin === $this->publicDomain) {
                if ($method === "POST")
                    $this->constructor->adjustOrder($result);
                else if ($method === "PUT") {
                    if (!$userGroup->getOnlinePayment() || ($userGroup->getOnlinePayment() && $this->isCurrentUser($result->getPaymentId(), $request)) )
                        throw new \Exception();
                    $result->setStatus("WAITING");
                }
            } else if ($origin === $this->adminDomain && ($method === "POST" || $method === "PUT")) {
                $this->constructor->adjustAdminOrder($result);
            }
        }
    }

    private function isCurrentUser($uuid, $request)
    {
        $userUuid = $request->query->get('id');
        $pattern = "/^[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}/";
        if ($uuid === null || preg_match($pattern, $uuid) === 0 || preg_match($pattern, $uuid) === false)
            return false;
        return $userUuid == $uuid;
    }
}