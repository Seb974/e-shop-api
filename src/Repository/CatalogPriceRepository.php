<?php

namespace App\Repository;

use App\Entity\CatalogPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CatalogPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogPrice[]    findAll()
 * @method CatalogPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogPrice::class);
    }

    // /**
    //  * @return CatalogPrice[] Returns an array of CatalogPrice objects
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
    public function findOneBySomeField($value): ?CatalogPrice
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
