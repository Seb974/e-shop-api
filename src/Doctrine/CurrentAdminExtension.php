<?php

namespace App\Doctrine;

use App\Entity\User;
use App\Entity\Seller;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Group;
use App\Entity\Touring;

class CurrentAdminExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;
    private $auth;
    private $userGroupDefiner;
    private $adminDomain;
    private $requestStack;

    public function __construct($requestStack, $admin, $public, Security $security, AuthorizationCheckerInterface $auth, UserGroupDefiner $userGroupDefiner)
    {
        $this->auth = $auth;
        $this->adminDomain = $admin;
        $this->security = $security;
        $this->publicDomain = $public;
        $this->requestStack = $requestStack;
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
        $request = $this->requestStack->getCurrentRequest();
        $origin = $request->headers->get('origin');

        if ($origin === $this->adminDomain && !$this->auth->isGranted('ROLE_ADMIN') && $user instanceof User)
        {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $userGroup = $this->userGroupDefiner->getUserGroup($user);

            if ( $resourceClass == Product::class ) {
                $queryBuilder->leftJoin("$rootAlias.seller","s")
                             ->leftJoin("s.users", "u")
                             ->andWhere("u IS NOT NULL")
                             ->andWhere(":user = u.id")
                             ->setParameter("user", $user->getId());
            }

            if ( $resourceClass == Seller::class ) {
                $queryBuilder->andWhere(":user MEMBER OF $rootAlias.users")
                             ->setParameter("user", $user);
            }

            if ( $resourceClass == Touring::class ) {
                $queryBuilder->leftJoin("$rootAlias.deliverer","u")
                             ->andWhere("u IS NOT NULL")
                             ->andWhere("u.id = :user")
                             ->setParameter("user", $user);
            }
        }
    }
}