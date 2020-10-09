<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    private function getAllQuery()
    {
        $query =  $this->createQueryBuilder('l')
                        ->select('l', 's', 'a', 'au', 'lu')
                        ->join('l.annonce', 'a')
                        ->join('a.user', 'au')
                        ->join('l.user', 'lu')
                        ->leftJoin('l.statutLocation', 's')
                        ->leftJoin('a.photo', 'p')
                        ->orderBy('l.dateDebut', 'DESC');
        return $query;
    }

    public function checkDates(array $reservations, int $annonce)
    {
        $query = $this->createQueryBuilder('l')
                    ->select('count(l.id)')
                    ->where('l.annonce = :annonce')
                    ->setParameter('annonce', $annonce);

        $i = 0;
        foreach( $reservations as $reservation )
        {
            $query = $query->andWhere("(:debut_$i <= l.dateDebut AND l.dateDebut <= :fin_$i) OR (:debut_$i <= l.dateFin AND l.dateFin <= :fin_$i)")
                            ->setParameter("debut_$i", $reservation->debut)
                            ->setParameter("fin_$i"  , $reservation->fin );
            $i++;
        }
        return $query->getQuery()->getSingleScalarResult() == 0;
    }

    public function findLocations(int $user_id)
    {
        return $this->getAllQuery()
                    ->where('au.id = :user_id')
                    ->setParameter('user_id', $user_id)
                    ->getQuery()
                    ->getResult();
    }

    public function findLocationsEnCours(int $user_id)
    {
        return $this->getAllQuery()
                    ->where('s.id = 2') // En cours
                    ->andWhere('au.id = :user_id')
                    ->setParameter('user_id', $user_id)
                    ->getQuery()
                    ->getResult();
    }

    public function findAbonnements(int $user_id)
    {
        return $this->getAllQuery()
                    ->where('lu.id = :user_id')
                    ->setParameter('user_id', $user_id)
                    ->getQuery()
                    ->getResult();
    }
    

    // /**
    //  * @return Location[] Returns an array of Location objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Location
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
