<?php

namespace App\EventSubscriber\Picture;

use App\Entity\Picture;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Vich\UploaderBundle\Storage\StorageInterface;
use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ResolvePictureContentUrlSubscriber
 *
 * Informations :
 * Replace the contentUrl value which originally contains the plain file path on the filesystem
 * with a usable URL to work with.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
final class ResolvePictureContentUrlSubscriber implements EventSubscriberInterface
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || !\is_a($attributes['resource_class'], Picture::class, true)) {
            return;
        }

        $pictures = $controllerResult;

        if (!is_iterable($pictures)) {
            $pictures = [$pictures];
        }

        foreach ($pictures as $picture) {
            if (!$picture instanceof Picture) {
                continue;
            }

            $picture->contentUrl = $this->storage->resolveUri($picture, 'file');
        }
    }
}