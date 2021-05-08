<?php

namespace App\Repository;

use App\Entity\CatalogTax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CatalogTax|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogTax|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogTax[]    findAll()
 * @method CatalogTax[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogTaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogTax::class);
    }

    // /**
    //  * @return CatalogTax[] Returns an array of CatalogTax objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CatalogTax
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
