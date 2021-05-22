<?php

namespace App\Serializer;

use App\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class GroupContextBuilder implements SerializerContextBuilderInterface
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

        if ($resourceClass === Group::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            if ($normalization)
                $context['groups'][] = 'admin:groups_read';
            else
                $context['groups'][] = 'admin:group_write';
        }

        return $context;
    }
}