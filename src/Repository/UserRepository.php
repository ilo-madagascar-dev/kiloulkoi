<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllisClients($lastUsername,$role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :lastUsername')
            ->andWhere('u.fonction = :role')
            ->setParameter('lastUsername', $lastUsername)
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult();
    }

    public function findAllisPrestataire($lastUsername,$role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :lastUsername')
            ->andWhere('u.fonction = :role')
            ->setParameter('lastUsername', $lastUsername)
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult();
    }

    public function findAllisAdmin($lastUsername,$role)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :lastUsername')
            ->andWhere('u.fonction = :role')
            ->setParameter('lastUsername', $lastUsername)
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult();
    }

    public function findAllUsers($lastUsername)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :lastUsername')
            ->setParameter('lastUsername', $lastUsername)
            ->getQuery()
            ->getResult();
    }

    public function countKilouwers( User $user )
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->join('u.kilouwers', 'k')
            ->where('u.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAnnonces( User $user )
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->join('u.annonces', 'a')
            ->where('u.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Mes kilouwers pr??f??r??s
     *
     * @param  User $user
     * @return \Doctrine\ORM\Query
     */
    public function userFavoris(User $user)
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'kk', 'count(kk.id)', 'count(a.id)')
            ->leftJoin('u.kilouwers', 'k')
            ->leftJoin('u.kilouwers', 'kk')
            ->leftJoin('u.annonces', 'a')
            ->where('k.id = :id')
            ->setParameter('id', $user->getId())
            ->groupBy('u.id')
            ->getQuery();
    }

    /**
     * Mes followers sur kiloukoi
     *
     * @param  User $user
     * @return \Doctrine\ORM\Query
     */
    public function kilouwers(User $user)
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'kk', 'count(kk.id)', 'count(a.id)')
            ->leftJoin('u.kilouwees', 'k')
            ->leftJoin('u.kilouwees', 'kk')
            ->leftJoin('u.annonces', 'a')
            ->where('k.id = :id')
            ->setParameter('id', $user->getId())
            ->groupBy('u.id')
            ->getQuery();
    }

    /**
     * isFollowedBy
     *
     * @param  User $proprietaire
     * @param  User $follower
     * @return int
     */
    public function isFollowedBy( User $proprietaire, User $follower )
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->join('u.kilouwers', 'k')
            ->where('u.id = :proprietaire_id')
            ->setParameter('proprietaire_id', $proprietaire->getId())
            ->andWhere('k.id = :follower_id')
            ->setParameter('follower_id', $follower->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
