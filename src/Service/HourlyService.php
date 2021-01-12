<?php

namespace App\Service;

use App\Entity\Abonnement;
use App\Entity\Location;
use App\Entity\Note;
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

            $locations = $locationRepository->toNote();

            foreach($locations as $location)
            {
                $note = new Note();
                $note->setValeur(5);
                $note->setLocation( $location );

                $this->em->persist($note);
            }

            $this->em->flush();
        }
        catch( Exception $e ) {}
    }

	protected function initialize(Schedule $schedule)
	{
		// $schedule->everyHours(12); // Perform the task every 24Hours
    }
}
