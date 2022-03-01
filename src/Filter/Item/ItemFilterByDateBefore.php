<?php

namespace App\Filter\Item;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Item;
use Doctrine\ORM\QueryBuilder;

final class ItemFilterByDateBefore extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Item::class && (!$this->isPropertyEnabled($property, $resourceClass) || !$this->isPropertyMapped($property, $resourceClass))) {
            return;
        } else if ($resourceClass == Item::class && $property != "before") {
            return;
        }
        
        $parameterName = $queryNameGenerator->generateParameterName($property);
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $before = new \DateTime($value);

        $queryBuilder
            ->leftJoin("$rootAlias.orderEntity","h")
            ->andWhere(sprintf('h.deliveryDate <= :%s', $parameterName))
            ->setParameter($parameterName, new \DateTime($before->format("Y-m-d")." 23:59:59"));
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
                    'description' => 'Filter Items to allow search comparing orderEntity delivery dates',
                    'name' => 'Order filter by dates',
                    'type' => ' ',
                ],
            ];
        }

        return $description;
    }
}