<?php
/**
* Created by PhpStorm.
* User: E6520
* Date: 20/07/2020
* Time: 13:47
*/
namespace App\Service;

use App\Entity\Abonnement;
use App\Entity\Location;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class SubscriptionService extends AbstractScheduledTask
{

	protected $em; 

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
        
    /**
     * - Renouvelle les abonnements actifs
     * - Met à jour le statut des locations
     *
     * @return void
     */
    public function run()
    {
        try {
            /**
             * @var App\Repository\UserRepository
             */
            $userRepo = $this->em->getRepository(User::class);

            
        }
        catch( Exception $e ) {}
    }


	protected function initialize(Schedule $schedule) 
	{
		// $schedule->everyHours(24); // Perform the task every 24Hours
    }
}