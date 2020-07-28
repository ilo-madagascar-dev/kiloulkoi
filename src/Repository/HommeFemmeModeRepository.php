<?php

namespace App\Repository;

use App\Entity\HommeFemmeMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HommeFemmeMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method HommeFemmeMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method HommeFemmeMode[]    findAll()
 * @method HommeFemmeMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HommeFemmeModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HommeFemmeMode::class);
    }

    // /**
    //  * @return HommeFemmeMode[] Returns an array of HommeFemmeMode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HommeFemmeMode
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
