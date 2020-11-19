<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findOneWith($expediteur_id, $destinataire_id)
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'l')
            ->leftJoin('c.locations', 'l')
            ->where('c.user_1 = :user_1 AND c.user_2 = :user_2 ')
            ->orWhere('c.user_1 = :user_2 AND c.user_2 = :user_1 ')

            ->setParameter('user_1', $expediteur_id)
            ->setParameter('user_2', $destinataire_id)

            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByUserQuery(int $user_id)
    {
        $msgDql = $this->getEntityManager()
                        ->getRepository(Message::class)
                        ->createQueryBuilder('s_m')
                        ->select('MAX(s_m.id)')
                        ->groupBy('s_m.conversation')
                        ->getDQL();

        return $this->createQueryBuilder('c')
            ->select('c', 'u1', 'u2', 'm', 'a')
            ->distinct()
            ->join('c.locations', 'a', Expr\Join::WITH)
            ->join('c.user_1', 'u1', Expr\Join::WITH)
            ->join('c.user_2', 'u2', Expr\Join::WITH)
            ->join('c.messages', 'm', Expr\Join::WITH)
            ->andWhere('c.user_1 = :user_id OR c.user_2 = :user_id')
            ->setParameter('user_id', $user_id)
            ->andwhere('m.id IN (' . $msgDql . ')')
            ->orderBy('m.date', 'DESC')
            ->getQuery();
    }

    public function countUnreadBy(User $user)
    {
        return $this->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.user_1 = :user_id AND c.lu_1 = FALSE')
                    ->orWhere('c.user_2 = :user_id AND c.lu_2 = FALSE')
                    ->setParameter('user_id', $user->getId() )
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
