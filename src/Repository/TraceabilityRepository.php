<?php

namespace App\Repository;

use App\Entity\Traceability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Traceability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traceability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traceability[]    findAll()
 * @method Traceability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraceabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traceability::class);
    }

    // /**
    //  * @return Traceability[] Returns an array of Traceability objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Traceability
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
