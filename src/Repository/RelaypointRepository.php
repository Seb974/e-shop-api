<?php

namespace App\Repository;

use App\Entity\Relaypoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Relaypoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relaypoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relaypoint[]    findAll()
 * @method Relaypoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelaypointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relaypoint::class);
    }

    /**
     * @return Relaypoint[] Returns an array of Seller objects
     */
    public function findUserRelaypoints($user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere(':user MEMBER OF r.managers')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Relaypoint[] Returns an array of Relaypoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Relaypoint
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
