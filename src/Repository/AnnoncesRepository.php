<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
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
        return $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->where('u.id = :id')
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

    public function findAnnonces(Array $criteria)
    {
        $query = $this->createQueryBuilder('a');

        if( !empty($criteria['categorie']) )
        {
            $query
                ->leftJoin('a.categorie', 'c')
                ->where('c.className = :class_name')->setParameter('class_name', $criteria['categorie']);

            // if( !empty($criteria['sous_categorie']) )
            // {
            //     $query
            //         ->leftJoin('a.sous_categorie', 'c')
            //         ->where('c.className = :class_name')->setParameter('class_name', $criteria['sous_categorie']);
            // }
        }

        return $query->getQuery()->getResult();
    }

}
