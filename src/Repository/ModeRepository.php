<?php

namespace App\Repository;

use App\Entity\Mode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mode[]    findAll()
 * @method Mode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mode::class);
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
    //  * @return Mode[] Returns an array of Mode objects
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
    public function findOneBySomeField($value): ?Mode
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
