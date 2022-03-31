<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Package|null find($id, $lockMode = null, $lockVersion = null)
 * @method Package|null findOneBy(array $criteria, array $orderBy = null)
 * @method Package[]    findAll()
 * @method Package[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    /**
     * @return Package[] Returns an array of Package objects
     */
    public function findReturnablesByEmail($email)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin("p.container","m")
            ->leftJoin("p.orderEntity","q")
            ->andWhere("q IS NOT NULL")
            ->andWhere('m.isReturnable = :isReturnable')
            ->setParameter('isReturnable', true)
            ->andWhere('q.email = :email')
            ->setParameter('email', $email)
            ->andWhere("p.returned IS NULL")
            ->orWhere("p.quantity < p.returned")
            ->orderBy("q.deliveryDate", 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Package
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
