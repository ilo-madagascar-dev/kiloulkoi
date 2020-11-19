<?php

namespace App\Repository;

use App\Entity\TailleMaternite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TailleMaternite|null find($id, $lockMode = null, $lockVersion = null)
 * @method TailleMaternite|null findOneBy(array $criteria, array $orderBy = null)
 * @method TailleMaternite[]    findAll()
 * @method TailleMaternite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TailleMaterniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TailleMaternite::class);
    }

    // /**
    //  * @return TailleMaternite[] Returns an array of TailleMaternite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TailleMaternite
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
