<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    /**
     * @return Query Returns an array of Abonnement objects
     */
    public function findLatest(int $user_id)
    {
        return $this->createQueryBuilder('a')
            ->select('a', 't')
            ->join('a.user', 'user')
            ->join('a.type', 't')
            ->where('user = :user_id')
            ->setParameter('user_id', $user_id)
            ->setMaxResults(10)
            ->getQuery()
        ;
    }


    /**
     * @return Abonnement
     */
    public function findUserAbonnement(int $user) : ?Abonnement
    {
        return $this->createQueryBuilder('a')
            ->select('a', 't')
            ->join('a.type', 't')
            ->where('a.user = :user_id')
            ->setParameter('user_id', $user)
            ->setParameter('now', date("Y-m-d"))
            ->andWhere('a.dateFin >= :now')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findOneBySomeField($value): ?Abonnement
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
