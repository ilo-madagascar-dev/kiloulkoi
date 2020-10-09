<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\VarExporter\Internal\Hydrator;

/**
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    private function getAllQuery()
    {
        $query =  $this->createQueryBuilder('a')
                        ->select('a', 'u', 'c', 'p')
                        ->join('a.user', 'u')
                        ->leftJoin('a.categorie', 'c')
                        ->leftJoin('a.photo', 'p')
                        ->orderBy('a.id', 'DESC')
                        ->orderBy('p.id', 'ASC');
        return $query;
    }

    /**
     * Annonces des autres utilisateurs
     * 
     * @return Annonces[] Returns an array of Annonces objects
     */

    public function findOtherAnnonceById($id)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->where('u.id != :id')
            ->setParameter('id', $id)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Annonces[] Returns an array of Annonces objects
     */
    public function findMesAnnonces($id)
    {
        $query = $this->getAllQuery();

        return $query->where('u.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
            ;
    }

    /**
     * @return Annonces[] Returns an array of Annonces objects
     */
    public function findLocationEncours($id)
    {
        $query = $this->getAllQuery();

        return $query->where('u.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
            ;
    }


    /**
     * @return Annonces[] Returns an array of Annonces objects
     */
    public function findAbonnements($id)
    {
        $query = $this->getAllQuery();

        return $query->where('u.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
            ;
    }

    /**
     * @return \Doctrine\ORM\Query
     */

    public function findAllAnnonces($limit = 0)
    {
        $query = $this->getAllQuery();

        if( $limit <= 0 )
        {
            return $query->getQuery();
        }
        else
        {
            return $query->setMaxResults($limit)
                        ->getQuery();
        }
    }

    /**
     * @return Annonces
     */
    public function findAnnonceById(int $id)
    {
        $query = $this->getAllQuery()
                    ->addSelect('l')
                    ->leftJoin('a.locations', 'l')
                    ->where('a.id = :id')
                    ->setParameter('id', $id)
                    // ->andWhere('l.statusLocation.id = 2') // En cours
                    // ->andWhere('l.dateFin >= :fin OR l.dateFin is null')
                    // ->setParameter('fin', date('Y-m-d'))
                    ->orderBy('l.dateDebut', 'ASC')
                    ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @return \Doctrine\ORM\Query
     */

    public function findAnnonces(Array $criteria)
    {
        $query = $this->getAllQuery();

        if( !empty($criteria['categorie']) )
        {
            $query->andWhere('c.className = :class_name')->setParameter('class_name', $criteria['categorie']);

        }

        if( !empty($criteria['titre']) )
        {
            $query->andWhere('a.titre LIKE :titre')->setParameter('titre', '%' . $criteria['titre'] . '%');
        }

        if( !empty($criteria['min_prix']) )
        {
            $query->andWhere('a.prix >= :min_prix')->setParameter('min_prix', $criteria['min_prix']);
        }

        if( !empty($criteria['max_prix']) )
        {
            $query->andWhere('a.prix <= :max_prix')->setParameter('max_prix', $criteria['max_prix']);
        }

        return $query->getQuery();
    }

}
