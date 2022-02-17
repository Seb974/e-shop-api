<?php

namespace App\Filter\Stock;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Stock;
use App\Repository\RelaypointRepository;
use Doctrine\ORM\QueryBuilder;

final class StockProductFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Stock::class && (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass))) {
            return;
        } else if ($resourceClass == Stock::class && $property != "productSearch") {
            return;
        }
        
        // $parameterName = $queryNameGenerator->generateParameterName($property);
        $rootAlias = $queryBuilder->getRootAliases()[0];
        if (intval($value) === 1) {
            $queryBuilder
                ->leftJoin("$rootAlias.product","p")
                ->leftJoin("$rootAlias.size","z")
                ->andWhere("p IS NOT NULL")
                ->orWhere("z IS NOT NULL");
        } else {
            $queryBuilder
                ->leftJoin("$rootAlias.product","p")
                ->leftJoin("$rootAlias.size","z")
                ->andWhere("p IS NULL")
                ->andWhere("z IS NULL");
        }
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
                    'description' => 'Filter orderEntities to allow search by relaypoint',
                    'name' => 'Order filter by relaypoint',
                    'type' => ' ',
                ],
            ];
        }

        return $description;
    }
}