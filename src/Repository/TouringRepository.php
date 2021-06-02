<?php

namespace App\Repository;

use App\Entity\Touring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Touring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Touring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Touring[]    findAll()
 * @method Touring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TouringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Touring::class);
    }

    // /**
    //  * @return Touring[] Returns an array of Touring objects
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
    public function findOneBySomeField($value): ?Touring
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
