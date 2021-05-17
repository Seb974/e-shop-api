<?php

namespace App\Doctrine;

use App\Entity\User;
use App\Entity\Group;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Promotion;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $auth;
    private $userGroupDefiner;

    public function __construct(Security $security, AuthorizationCheckerInterface $auth, UserGroupDefiner $userGroupDefiner)
    {
        $this->security = $security;
        $this->auth = $auth;
        $this->userGroupDefiner = $userGroupDefiner;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $user = $this->security->getUser();
        $group = [Group::class];
        $groupFilterable = [Category::class, Product::class];
        $needingAvailability = [Promotion::class];
        $userGroup = $this->userGroupDefiner->getUserGroup($user);

        if (!$this->auth->isGranted('ROLE_ADMIN') && ($user instanceof User || $user == null)) {
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if (in_array($resourceClass, $group)) {
                $queryBuilder->andWhere("$rootAlias.id = :userGroupId")
                             ->setParameter("userGroupId", $userGroup->getId());
            }

            if (in_array($resourceClass, $groupFilterable)) {
                $queryBuilder->andWhere(":userGroup MEMBER OF $rootAlias.userGroups")
                             ->setParameter("userGroup", $userGroup);
            }

            if (in_array($resourceClass, $needingAvailability)) {
                $queryBuilder->andWhere("$rootAlias.used < $rootAlias.maxUsage")
                             ->andWhere("$rootAlias.endsAt >= :today")
                             ->orWhere("$rootAlias.code = :code")
                             ->setParameter("today", new \DateTime())
                             ->setParameter("code", "relaypoint");
            }
        }
    }
}