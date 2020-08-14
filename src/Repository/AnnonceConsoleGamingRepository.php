<?php

namespace App\Repository;

use App\Entity\AnnonceConsoleGaming;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceConsoleGaming|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceConsoleGaming|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceConsoleGaming[]    findAll()
 * @method AnnonceConsoleGaming[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceConsoleGamingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceConsoleGaming::class);
    }

    // /**
    //  * @return AnnonceConsoleGaming[] Returns an array of AnnonceConsoleGaming objects
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
    public function findOneBySomeField($value): ?AnnonceConsoleGaming
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
