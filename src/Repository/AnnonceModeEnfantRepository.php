<?php

namespace App\Repository;

use App\Entity\AnnonceModeEnfant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceModeEnfant|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceModeEnfant|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceModeEnfant[]    findAll()
 * @method AnnonceModeEnfant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceModeEnfantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceModeEnfant::class);
    }

    // /**
    //  * @return AnnonceModeEnfant[] Returns an array of AnnonceModeEnfant objects
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
    public function findOneBySomeField($value): ?AnnonceModeEnfant
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
