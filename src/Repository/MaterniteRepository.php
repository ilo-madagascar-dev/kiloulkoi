<?php

namespace App\Repository;

use App\Entity\Maternite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Maternite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maternite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maternite[]    findAll()
 * @method Maternite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maternite::class);
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
    //  * @return Maternite[] Returns an array of Maternite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Maternite
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
