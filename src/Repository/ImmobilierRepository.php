<?php

namespace App\Repository;

use App\Entity\Immobilier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Immobilier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Immobilier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Immobilier[]    findAll()
 * @method Immobilier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImmobilierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Immobilier::class);
    }

    public function findOneArrayById($value): ?array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.photo', 'p')
            ->addSelect('p')
            ->andWhere('a.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        ;
    }

    // /**
    //  * @return Immobilier[] Returns an array of Immobilier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Immobilier
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
