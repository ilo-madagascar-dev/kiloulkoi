<?php

namespace App\Repository;

use App\Entity\TypeLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeLocation[]    findAll()
 * @method TypeLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeLocation::class);
    }

    // /**
    //  * @return TypeLocation[] Returns an array of TypeLocation objects
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
    public function findOneBySomeField($value): ?TypeLocation
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
