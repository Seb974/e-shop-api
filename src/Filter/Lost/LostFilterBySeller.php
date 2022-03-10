<?php

namespace App\Filter\Lost;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Lost;
use Doctrine\ORM\QueryBuilder;

final class LostFilterBySeller extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Lost::class && (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass))) {
            return;
        } else if ($resourceClass == Lost::class && $property != "seller") {
            return;
        }
        
        $parameterName = $queryNameGenerator->generateParameterName($property);
        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->leftJoin("$rootAlias.product","p")
            ->leftJoin("$rootAlias.stock","s")
            ->leftJoin("p.seller", "v")
            ->andWhere('s.platform IS NOT NULL')
            ->andWhere(sprintf('v.id = :%s', $parameterName))
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
                    'description' => 'Filter Losts to allow search by seller',
                    'name' => 'Lost filter by seller',
                    'type' => ' ',
                ],
            ];
        }

        return $description;
    }
}