<?php

namespace App\Repository;

use App\Entity\AnnonceImmobilier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceImmobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceImmobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceImmobilier[]    findAll()
 * @method AnnonceImmobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceImmobilierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceImmobilier::class);
    }

    // /**
    //  * @return AnnonceImmobilier[] Returns an array of AnnonceImmobilier objects
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
    public function findOneBySomeField($value): ?AnnonceImmobilier
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
