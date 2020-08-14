<?php

namespace App\Repository;

use App\Entity\AnnonceElectromenager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceElectromenager|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceElectromenager|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceElectromenager[]    findAll()
 * @method AnnonceElectromenager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceElectromenagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceElectromenager::class);
    }

    // /**
    //  * @return AnnonceElectromenager[] Returns an array of AnnonceElectromenager objects
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
    public function findOneBySomeField($value): ?AnnonceElectromenager
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
