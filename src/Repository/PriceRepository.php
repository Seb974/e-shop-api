<?php

namespace App\Repository;

use App\Entity\Price;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Price|null find($id, $lockMode = null, $lockVersion = null)
 * @method Price|null findOneBy(array $criteria, array $orderBy = null)
 * @method Price[]    findAll()
 * @method Price[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    // /**
    //  * @return Price[] Returns an array of Price objects
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

    public function findDefaultPrice(Product $product): ?Price
    {
        return $this->createQueryBuilder('p')
            ->leftJoin("p.product","o")
            ->leftJoin("p.priceGroup","g")
            ->leftJoin("g.userGroup","u")
            ->andWhere('o.id = :productId')
            ->setParameter('productId', $product->getId())
            ->andWhere('u.value = :defaultGroup')
            ->setParameter('defaultGroup', "ROLE_USER")
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
