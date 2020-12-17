<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }
    
    /**
     * count unread notifications
     *
     * @param  User $user
     * @return int
     */
    public function countUnread(User $user)
    {
        $query = $this->createQueryBuilder('n')
                    ->select( 'count(n.id)' )
                    ->where('n.destinataire = :user and n.lu = 0')
                    ->setParameter('user', $user->getId())
                    ->groupBy('n.destinataire')
                    ->getQuery();
        try {
            return $query->getSingleScalarResult();
        } 
        catch( Exception $e )
        {
            return 0;
        }
    }

    
    /**
     * User notifications
     *
     * @param  User $user
     * @return Notification[]
     */
    public function findAllByUser(User $user)
    {
        return $this->createQueryBuilder('n')
                    ->where('n.destinataire = :user')
                    ->setParameter('user', $user->getId())
                    ->setMaxResults(20)
                    ->getQuery()
                    ->getResult();
    }
}
