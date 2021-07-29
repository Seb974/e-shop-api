<?php

namespace App\EventSubscriber\Picture;

use Imgix\UrlBuilder;
use App\Entity\Picture;
use App\Service\Image\Dimension;
use Doctrine\ORM\EntityManagerInterface;
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
    private $em;
    private $key;
    private $domain;
    private $dimension;

    public function __construct($key, $domain, EntityManagerInterface $em, Dimension $dimension)
    {
        $this->em = $em;
        $this->key = $key;
        $this->domain = $domain;
        $this->dimension = $dimension;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addImgIXUrl', EventPriorities::PRE_SERIALIZE]
        ];
    }

    public function addImgIXUrl(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Picture && ($method === "POST" || $method === "PUT" || $method === "PATCH")) {
            $params = $this->getParams($result);
            $builder = new UrlBuilder($this->domain, true, $this->key, false);
            $imgPath = $builder->createURL($result->filePath, $params);
            $result->setImgPath($imgPath);
            $this->em->flush();
        }
    }

    private function getParams($picture)
    {
        $entity = $picture->getLinkInstance();
        $sizes = getimagesize($picture->file);
        $width = $this->dimension->getWidth($entity);
        $height = $this->dimension->getHeight($entity);
        $params = [
            "w" => $width,
            "h" => $height, 
            "fit" => "crop", 
            "crop" => "edges",
             "auto" => "format"
        ];

        if ($sizes[0] < $width || $sizes[1] < $height) {
            $params = array_merge($params, ["dpr" => 0.9]);
        }
        return $params;
    }
}