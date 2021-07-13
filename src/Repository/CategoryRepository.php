<?php

namespace App\Repository;

use App\Entity\Catalog;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Category objects
     */
    public function findRestrictedCategoriesByCatalog(Catalog $catalog)
    {
        return $this->createQueryBuilder('c')
                    ->join('c.restrictions', 'r')
                    ->andWhere(':catalog MEMBER OF c.catalogs')
                    ->setParameter('catalog', $catalog)
                    ->getQuery()
                    ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()

            // ->orderBy('c.id', 'ASC')
            // ->setMaxResults(10)
        ;
    }
    */
}
