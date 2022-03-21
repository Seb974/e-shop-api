<?php

namespace App\EventSubscriber\Picture;

use Imgix\UrlBuilder;
use App\Entity\Picture;
use App\Service\Image\Dimension;
use App\Repository\SellerRepository;
use App\Repository\PlatformRepository;
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
    private $dimension;
    private $sellerRepository;
    private $platformRepository;

    public function __construct(EntityManagerInterface $em, Dimension $dimension, PlatformRepository $platformRepository, SellerRepository $sellerRepository)
    {
        $this->em = $em;
        $this->dimension = $dimension;
        $this->sellerRepository = $sellerRepository;
        $this->platformRepository = $platformRepository;
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
        $request = $event->getRequest();
        $method = $request->getMethod();
        $owner = $this->getOwner($request->query->get('owner'));

        if ($result instanceof Picture && ($method === "POST" || $method === "PUT" || $method === "PATCH")) {
            $params = $this->getParams($result);
            $builder = new UrlBuilder($owner->getImgDomain(), true, $owner->getImgKey(), false);
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

    private function getOwner($ownerId)
    {
        if (!is_null($ownerId)) {
            return $this->sellerRepository->find($ownerId);
        }
        return $this->platformRepository->find(1);
    }
}