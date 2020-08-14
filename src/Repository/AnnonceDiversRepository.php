<?php

namespace App\Repository;

use App\Entity\AnnonceDivers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceDivers|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceDivers|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceDivers[]    findAll()
 * @method AnnonceDivers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceDiversRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceDivers::class);
    }

    // /**
    //  * @return AnnonceDivers[] Returns an array of AnnonceDivers objects
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
    public function findOneBySomeField($value): ?AnnonceDivers
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
