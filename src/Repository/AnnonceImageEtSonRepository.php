<?php

namespace App\Repository;

use App\Entity\AnnonceImageEtSon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceImageEtSon|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceImageEtSon|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceImageEtSon[]    findAll()
 * @method AnnonceImageEtSon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceImageEtSonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceImageEtSon::class);
    }

    // /**
    //  * @return AnnonceImageEtSon[] Returns an array of AnnonceImageEtSon objects
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
    public function findOneBySomeField($value): ?AnnonceImageEtSon
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
