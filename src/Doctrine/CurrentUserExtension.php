<?php

namespace App\Doctrine;

use App\Entity\User;
use App\Entity\Group;
use App\Entity\Seller;
use App\Entity\Touring;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Promotion;
use App\Entity\OrderEntity;
use Doctrine\ORM\QueryBuilder;
use App\Service\User\UserGroupDefiner;
use App\Repository\PlatformRepository;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Stock;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $auth;
    private $userGroupDefiner;
    private $requestStack;
    private $publicDomain;
    private $platformRepository;

    public function __construct($requestStack, $admin, $public, Security $security, AuthorizationCheckerInterface $auth, UserGroupDefiner $userGroupDefiner, PlatformRepository $platformRepository)
    {
        $this->auth = $auth;
        $this->adminDomain = $admin;
        $this->security = $security;
        $this->publicDomain = $public;
        $this->requestStack = $requestStack;
        $this->userGroupDefiner = $userGroupDefiner;
        $this->platformRepository = $platformRepository;
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
        $request = $this->requestStack->getCurrentRequest();
        $origin = $request->headers->get('origin');

        // if ($origin === $this->publicDomain && !$this->auth->isGranted('ROLE_ADMIN') && ($user instanceof User || $user == null))
        if ($origin === $this->publicDomain && ($user instanceof User || $user == null))
        {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            if ($resourceClass == Stock::class) {
                $platform = $this->platformRepository->find(1);
                $queryBuilder->leftJoin("$rootAlias.platform","p")
                             ->andWhere("p IS NOT NULL")
                             ->andWhere("p.id = :platformId")
                             ->setParameter("platformId", $platform->getId());
            }
            else if (!$this->auth->isGranted('ROLE_ADMIN'))
            {
                $userGroup = $this->userGroupDefiner->getUserGroup($user);
                $group = [Group::class];
                $needingAvailability = [Promotion::class];
                $groupFilterable = [Category::class];

                if (in_array($resourceClass, $group)) {
                    $queryBuilder->andWhere("$rootAlias.id = :userGroupId")
                                ->setParameter("userGroupId", $userGroup->getId());
                }

                if (in_array($resourceClass, $groupFilterable)) {
                    $queryBuilder->andWhere(":userGroup MEMBER OF $rootAlias.userGroups")
                                ->setParameter("userGroup", $userGroup);
                }

                if (in_array($resourceClass, $needingAvailability)) {
                    $queryBuilder->andWhere("$rootAlias.used is NULL OR $rootAlias.used < $rootAlias.maxUsage")
                                ->andWhere("$rootAlias.endsAt is NULL OR $rootAlias.endsAt >= :today")
                                ->setParameter("today", new \DateTime());
                }

                if ($resourceClass == Touring::class && !is_null($user)) {
                    $queryBuilder->leftJoin("$rootAlias.orderEntities","z")
                                ->leftJoin("z.user", "u")
                                ->andWhere("u IS NOT NULL")
                                ->andWhere(":userId = u.id")
                                ->setParameter("userId", $user->getId())
                                ->andWhere("$rootAlias.isOpen = :open")
                                ->setParameter("open", true);
                }

                if ($resourceClass == OrderEntity::class && !is_null($user)) {
                    $queryBuilder->leftJoin("$rootAlias.user","u")
                                ->andWhere("u IS NOT NULL")
                                ->andWhere("u.id = :userId")
                                ->setParameter("userId", $user->getId());
                }

                if ($resourceClass == Product::class && ($user == null || (count($user->getRoles()) == 1 && $this->auth->isGranted('ROLE_USER')))) {
                    $queryBuilder->andWhere("$rootAlias.available = :available")
                                ->setParameter("available", true)
                                ->andWhere(":userGroup MEMBER OF $rootAlias.userGroups")
                                ->setParameter("userGroup", $userGroup);
                }
            }
        }
    }
}