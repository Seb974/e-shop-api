<?php

namespace App\Repository;

use App\Entity\PriceGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PriceGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PriceGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PriceGroup[]    findAll()
 * @method PriceGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceGroup::class);
    }

    // /**
    //  * @return PriceGroup[] Returns an array of PriceGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PriceGroup
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
