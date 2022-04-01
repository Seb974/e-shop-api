<?php

namespace App\Filter\Package;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Package;
use Doctrine\ORM\QueryBuilder;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Bridge\Doctrine\Common\PropertyHelperTrait;
use ApiPlatform\Core\Bridge\Doctrine\Orm\PropertyHelperTrait as OrmPropertyHelperTrait;
use ApiPlatform\Core\Util\RequestParser;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class PackageFilterNeedingReturns extends AbstractContextAwareFilter
{

    private $em;

    public function __construct(ManagerRegistry $managerRegistry, ?RequestStack $requestStack = null, LoggerInterface $logger = null, array $properties = null, NameConverterInterface $nameConverter = null, EntityManagerInterface $em) {
        Parent::__construct($managerRegistry, $requestStack, $logger, $properties, $nameConverter);
        $this->em = $em;
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Package::class && (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass))) {
            return;
        } else if ($resourceClass == Package::class && $property != "needsReturn") {
            return;
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->leftJoin("$rootAlias.container","m")
            ->leftJoin("$rootAlias.orderEntity","q")
            ->andWhere("q IS NOT NULL")
            ->andWhere(sprintf('m.isReturnable = (:%s)', $parameterName))
            ->setParameter($parameterName, true)
            ->andWhere("$rootAlias.returned IS NULL")
            ->orWhere("$rootAlias.quantity > $rootAlias.returned");
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["regexp_$property"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter package that needs returns',
                    'name' => 'Package filter needing retirns',
                    'type' => ' ',
                ],
            ];
        }

        return $description;
    }
}