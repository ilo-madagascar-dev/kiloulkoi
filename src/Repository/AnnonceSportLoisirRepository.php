<?php

namespace App\Repository;

use App\Entity\AnnonceSportLoisir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceSportLoisir|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceSportLoisir|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceSportLoisir[]    findAll()
 * @method AnnonceSportLoisir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceSportLoisirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceSportLoisir::class);
    }

    // /**
    //  * @return AnnonceSportLoisir[] Returns an array of AnnonceSportLoisir objects
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
    public function findOneBySomeField($value): ?AnnonceSportLoisir
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
