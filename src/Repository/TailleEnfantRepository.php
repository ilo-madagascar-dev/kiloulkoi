<?php

namespace App\Repository;

use App\Entity\TailleEnfant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TailleEnfant|null find($id, $lockMode = null, $lockVersion = null)
 * @method TailleEnfant|null findOneBy(array $criteria, array $orderBy = null)
 * @method TailleEnfant[]    findAll()
 * @method TailleEnfant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TailleEnfantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TailleEnfant::class);
    }

    // /**
    //  * @return TailleEnfant[] Returns an array of TailleEnfant objects
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
    public function findOneBySomeField($value): ?TailleEnfant
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
