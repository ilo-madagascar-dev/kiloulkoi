<?php

namespace App\Repository;

use App\Entity\AnnonceModeFemme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceModeFemme|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceModeFemme|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceModeFemme[]    findAll()
 * @method AnnonceModeFemme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceModeFemmeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceModeFemme::class);
    }

    // /**
    //  * @return AnnonceModeFemme[] Returns an array of AnnonceModeFemme objects
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
    public function findOneBySomeField($value): ?AnnonceModeFemme
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
