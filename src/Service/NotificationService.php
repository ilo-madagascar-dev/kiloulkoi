<?php
namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class NotificationService
{
    private $publisher;
    private $em;

    public function __construct(PublisherInterface $publisher, EntityManagerInterface $em)
    {
        $this->publisher = $publisher;
        $this->em = $em;
    }

    public function send(Notification $notification, User $destinataire)
    {
        /**
         * @var App\Repository\NotificationRepository
         */
        $notifRepo  = $this->em->getRepository(Notification::class);

        $serialized = json_encode([
            'content' => $notification->getMessage(),
            'date'    => $notification->getDateCreation()->format('d/m/Y | H:m'),
            'lien'    => $notification->getRoute(),
            'photo'   => $notification->getPhoto(),
            'unread'  => $notifRepo->countUnread($destinataire),
            'id'      => $notification->getId(),
            'lu'      => $notification->getLu(),
            'type'    => 'notification',
        ]);

        try {
            ($this->publisher)( new Update(
                [ "http://127.0.0.1:8080/event/" . $destinataire->getId() ],
                $serialized,
                true,
            ));
        } catch( Exception $e ) {}
    }
}