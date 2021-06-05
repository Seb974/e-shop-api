<?php

namespace App\Repository;

use App\Entity\Deliverer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deliverer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deliverer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deliverer[]    findAll()
 * @method Deliverer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelivererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deliverer::class);
    }

    /**
     * @return Deliverer[] Returns an array of Deliverer objects
     */
    public function findUserDeliverers($user)
    {
        return $this->createQueryBuilder('d')
            ->andWhere(':user MEMBER OF d.users')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Deliverer
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
