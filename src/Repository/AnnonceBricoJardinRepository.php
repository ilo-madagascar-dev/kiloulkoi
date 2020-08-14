<?php

namespace App\Repository;

use App\Entity\AnnonceBricoJardin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceBricoJardin|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceBricoJardin|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceBricoJardin[]    findAll()
 * @method AnnonceBricoJardin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceBricoJardinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceBricoJardin::class);
    }

    // /**
    //  * @return AnnonceBricoJardin[] Returns an array of AnnonceBricoJardin objects
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
    public function findOneBySomeField($value): ?AnnonceBricoJardin
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
