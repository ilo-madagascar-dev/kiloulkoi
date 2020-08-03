<?php

namespace App\Repository;

use App\Entity\EnfantMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EnfantMode|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnfantMode|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnfantMode[]    findAll()
 * @method EnfantMode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnfantModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnfantMode::class);
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
    //  * @return EnfantMode[] Returns an array of EnfantMode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EnfantMode
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
