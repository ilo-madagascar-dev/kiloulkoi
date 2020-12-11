<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
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
            ->andWhere('a.dateFin >= :now')
            ->setParameter('now', date("Y-m-d"))
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Abonnement[]
     */
    public function findOutdated()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 't', 'u')
            ->join('a.type', 't')
            ->join('a.user', 'u')
            ->leftJoin('App\Entity\Abonnement', '_a', Expr\Join::WITH, 'a.user = _a.user AND a.id < _a.id')
            ->where('_a.id is null')
            ->andWhere('a.actif = 1')
            ->andWhere('a.dateFin < :now')
            ->setParameter('now', date('Y-m-d'))
            ->getQuery()
            ->getResult();
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
