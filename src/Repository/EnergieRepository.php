<?php

namespace App\Repository;

use App\Entity\Energie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Energie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Energie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Energie[]    findAll()
 * @method Energie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnergieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Energie::class);
    }

    // /**
    //  * @return Energie[] Returns an array of Energie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Energie
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
