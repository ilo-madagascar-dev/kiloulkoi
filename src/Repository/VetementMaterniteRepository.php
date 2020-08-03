<?php

namespace App\Repository;

use App\Entity\VetementMaternite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VetementMaternite|null find($id, $lockMode = null, $lockVersion = null)
 * @method VetementMaternite|null findOneBy(array $criteria, array $orderBy = null)
 * @method VetementMaternite[]    findAll()
 * @method VetementMaternite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VetementMaterniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VetementMaternite::class);
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
    //  * @return VetementMaternite[] Returns an array of VetementMaternite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VetementMaternite
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
