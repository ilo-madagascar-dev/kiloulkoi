<?php

namespace App\Repository;

use App\Entity\StatutLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutLocation[]    findAll()
 * @method StatutLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutLocation::class);
    }

    // /**
    //  * @return StatutLocation[] Returns an array of StatutLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatutLocation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
