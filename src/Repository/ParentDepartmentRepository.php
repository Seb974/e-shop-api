<?php

namespace App\Repository;

use App\Entity\ParentDepartment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParentDepartment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParentDepartment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParentDepartment[]    findAll()
 * @method ParentDepartment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentDepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParentDepartment::class);
    }

    // /**
    //  * @return ParentDepartment[] Returns an array of ParentDepartment objects
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
    public function findOneBySomeField($value): ?ParentDepartment
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
