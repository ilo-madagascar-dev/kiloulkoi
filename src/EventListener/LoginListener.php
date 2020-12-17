<?php
namespace App\EventListener;

use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $em;
    private $session;
    private $conversationRepo;

    public function __construct(Session $session, EntityManagerInterface $em, ConversationRepository $conversationRepository)
    {
        $this->em = $em;
        $this->session = $session;
        $this->conversationRepo = $conversationRepository;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
    }
}