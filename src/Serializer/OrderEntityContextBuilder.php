<?php

namespace App\Serializer;

use App\Entity\OrderEntity;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class OrderEntityContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        if ( $resourceClass === OrderEntity::class && isset($context['groups']) ) {
            if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                $context['groups'][] = $normalization ? 'admin:orders_read' : 'admin:order_write';
            } 
        }

        return $context;
    }
}