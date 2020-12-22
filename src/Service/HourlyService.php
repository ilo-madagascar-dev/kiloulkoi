<?php

namespace App\Service;

use App\Entity\Abonnement;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class HourlyService extends AbstractScheduledTask
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
             * @var App\Repository\LocationRepository
             */
            $locationRepository  = $this->em->getRepository(Location::class);
            $locationRepository->updateLocationStatus();
        }
        catch( Exception $e ) {}
    }

	protected function initialize(Schedule $schedule)
	{
		// $schedule->everyHours(12); // Perform the task every 24Hours
    }
}
