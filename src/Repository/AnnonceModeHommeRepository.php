<?php

namespace App\Repository;

use App\Entity\AnnonceModeHomme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceModeHomme|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceModeHomme|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceModeHomme[]    findAll()
 * @method AnnonceModeHomme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceModeHommeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceModeHomme::class);
    }

    // /**
    //  * @return AnnonceModeHomme[] Returns an array of AnnonceModeHomme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnnonceModeHomme
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
