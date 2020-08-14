<?php

namespace App\Repository;

use App\Entity\AnnonceMeubleDeco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceMeubleDeco|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceMeubleDeco|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceMeubleDeco[]    findAll()
 * @method AnnonceMeubleDeco[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceMeubleDecoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceMeubleDeco::class);
    }

    // /**
    //  * @return AnnonceMeubleDeco[] Returns an array of AnnonceMeubleDeco objects
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
    public function findOneBySomeField($value): ?AnnonceMeubleDeco
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
