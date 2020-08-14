<?php

namespace App\Repository;

use App\Entity\AnnonceMaternite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceMaternite|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceMaternite|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceMaternite[]    findAll()
 * @method AnnonceMaternite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceMaterniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceMaternite::class);
    }

    // /**
    //  * @return AnnonceMaternite[] Returns an array of AnnonceMaternite objects
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
    public function findOneBySomeField($value): ?AnnonceMaternite
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
