<?php

namespace App\Filter\Product;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;

final class ProductFilterByGroupFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Product::class && (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass))) {
            return;
        } else if ($resourceClass == Product::class && $property != "group") {
            return;
        }
        
        $parameterName = $queryNameGenerator->generateParameterName($property);
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->leftJoin("$rootAlias.userGroups","r")
            ->andWhere(sprintf('r.value IN (:%s)', $parameterName))
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