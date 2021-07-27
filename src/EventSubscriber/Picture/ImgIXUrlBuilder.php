<?php

namespace App\EventSubscriber\Picture;

use Imgix\UrlBuilder;
use App\Entity\Picture;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ImgIXUrlBuilder
 *
 * Informations :
 * When a product is created or updated, this event subscriber updates its 'updatedAt' value.
 * This purpose allow Mercure to automatically send an event concerning the Product's update 
 * even when it's not the product itself that's updated but one of its dependances.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class ImgIXUrlBuilder implements EventSubscriberInterface 
{
    private $key;
    private $domain;
    private $builder;

    public function __construct($key, $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->builder = $this->getImgIXUrlBuilder();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addImgIXUrl', EventPriorities::PRE_WRITE]
        ];
    }

    public function addImgIXUrl(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Picture && $method === "POST") {
            $params = $this->getParams($result);
            $imgPath = $this->builder->createURL($result->filePath, $params);
            dump($imgPath);
            $result->setImgPath($imgPath);
        }
    }

    private function getImgIXUrlBuilder()
    {
        $builder = new UrlBuilder($this->domain);
        $builder->setSignKey($this->key);
        $builder->setUseHttps(true);
        $builder->setIncludeLibraryParam(false);
        return $builder;
    }

    private function getParams($picture)
    {
        $entity = $picture->getLinkInstance();
        $width = $entity === "product" ? 600 : 750;
        $height = $entity === "product" ? 800 : 440;
        return ["w" => $width, "h" => $height, "fit" => "crop", "crop" => "edges"];
    }
}