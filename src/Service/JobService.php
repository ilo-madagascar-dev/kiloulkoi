<?php
/**
* Created by PhpStorm.
* User: E6520
* Date: 20/07/2020
* Time: 13:47
*/
namespace App\Service;

use App\Entity\Abonnement;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use App\Entity\User;

class JobService extends AbstractScheduledTask
{

	protected $em; 
	// protected $publisher; 
	
	// public function __construct(EntityManagerInterface $em, PublisherInterface $publisher)
	// {
    //     $this->em = $em;
    //     $this->publisher = $publisher;
	// }
	
	// public function run()
	// {
	// 	$expediteur   = $this->em->getRepository(User::class)->find(6);
	// 	$destinataire = $this->em->getRepository(User::class)->find(5);

    //     $serialized = json_encode( [
    //         'user' => [
    //             'id'       => $expediteur->getId(),
    //             'avatar'   =>  '/uploads/avatar/' . $expediteur->getAvatar(),
    //             'fullName' => $expediteur->getNomComplet()
    //         ],
    //         'content' => date('YYYY-mm-dd HH:mm'),
    //         'date'    => date('d/m/Y | H:m'),
    //         'conversation' => 1,
    //         'path'    => "/conversation/1",
    //         'unread'  => 3
    //     ]);

    //     ($this->publisher)( new Update(
    //         [ "http://127.0.0.1:8080/message/" . $destinataire->getId() ],
    //         $serialized,
    //         true,
    //     ));
    // }
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function run()
    {
        try {
            /**
             * var App\Entity\Abonnement[]
             */
            $toRenew = $this->em->getRepository(Abonnement::class)->findBy(['actif' => 1]);

            foreach ($toRenew as $a )
            {
                $typeAbonnement = $a->getType();
                $debut          = new \Datetime();

                $a->setDateDebut( $debut );
                $a->setDateFin( $debut->add(new \DateInterval('P1M')) );
                $a->setActif( 1 );
                $a->setType( $typeAbonnement );
                $a->setUser( $a->getUser() );

                $this->em->persist($a);
            }
            $this->em->flush();
        }
        catch( Exception $e ) {}
    }


	protected function initialize(Schedule $schedule) 
	{
		$schedule->everyMinutes(1); // Perform the task every minutes
    }
}