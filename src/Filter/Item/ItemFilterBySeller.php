<?php

namespace App\Filter\Item;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Item;
use Doctrine\ORM\QueryBuilder;

final class ItemFilterBySeller extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Item::class && (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass))) {
            return;
        } else if ($resourceClass == Item::class && $property != "seller") {
            return;
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->leftJoin("$rootAlias.product", "b")
            ->leftJoin("b.seller", "q")
            ->andWhere(sprintf('q.id IN (:%s)', $parameterName))
            ->setParameter($parameterName, $value);
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
                    'description' => 'Filter orderEntities to allow search by seller',
                    'name' => 'Order filter by seller',
                    'type' => ' ',
                ],
            ];
        }

        return $description;
    }
}