<?php

namespace App\Service;

use App\Entity\Abonnement;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class DailyService extends AbstractScheduledTask
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
             * @var App\Repository\AbonnementRepository
             */
            $abRepo  = $this->em->getRepository(Abonnement::class);
            $toRenew = $abRepo->findOutdated();
            $debut   = new \Datetime();
            $fin     = (new \Datetime())->add(new \DateInterval('P1M'));

            foreach ($toRenew as $a )
            {
                $abonnement = new Abonnement();

                $abonnement->setDateDebut( $debut );
                $abonnement->setDateFin( $fin );
                $abonnement->setActif( 1 );
                $abonnement->setType( $a->getType() );
                $abonnement->setUser( $a->getUser() );

                $this->em->persist($abonnement);
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
