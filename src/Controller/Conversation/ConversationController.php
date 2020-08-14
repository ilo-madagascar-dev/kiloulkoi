<?php

namespace App\Controller\Conversation;

use App\Entity\Annonces;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conversation")
 */
class ConversationController extends AbstractController
{
    private $conversationRepo;
    private $messageRepo;

    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $this->conversationRepo = $conversationRepository;
        $this->messageRepo      = $messageRepository;
    }

    /**
     * @Route("/", name="conversation_index", methods={"get"})
     */
    public function index()
    {
        $user          = $this->getUser();
        $conversations = $this->conversationRepo->findByUserQuery($user->getId())->getResult();

        if( count($conversations) > 0 )
        {
            /**
             * @var(Conversation $conversationEncours)
             */
            $conversationEncours = $conversations[0];
            return $this->showConversation($conversationEncours, $conversations);
        }
        else
        {
            return $this->render('conversation/erreur.html.twig', [
                'error' => "Vous n'avez aucune conversation!"
            ]);
        }
    }


    /**
     * @Route("/messages/{annonce}/{destinataire}", name="conversation_messages_new", methods={"post"})
     */
    public function nouveauMessage(Annonces $annonce, User $destinataire, Request $request)
    {
        $expediteur = $this->getUser();
        if( $destinataire->getId() == $expediteur->getId() )
        {
            dump('Vous ne pouvez pas envoyer un message à vous meme');die;
        }

        $message      = new Message();
        $conversation = $this->conversationRepo->findOneWith($destinataire->getId(), $expediteur->getId(), $annonce->getId());

        if( $conversation == null )
        {
            $conversation = new Conversation();

            $conversation->setUser1($expediteur);
            $conversation->setUser2($destinataire);
            $conversation->setAnnonce($annonce);
        }

        $message->setConversation($conversation);
        $message->setUser($expediteur);
        $message->setContenue($request->get("contenue"));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($conversation);
        $entityManager->persist($message);
        $entityManager->flush();

        $conversation->getMessages()->add($message);

        return $this->showConversation($conversation);
    }

    /**
     * @Route("/{id}", name="conversation_show", methods={"get"})
     */
    public function show(Conversation $conversation): Response
    {
        return $this->showConversation($conversation);
    }

    /**
     * @Route("/{id}", name="conversation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Conversation $conversation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conversation->getId(), $request->request->get('_token')))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($conversation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conversation_index');
    }

    private function showConversation(Conversation $conversation, Array $conversations = [])
    {
        $user = $this->getUser();
        $conversationEncours = $conversation;
    
        $messages     = $this->messageRepo->findByConversation($conversationEncours->getId())->getResult();
        $destinataire = ($user->getId() == $conversationEncours->getUser1()->getId()) ? $conversationEncours->getUser2() : $conversationEncours->getUser1();
 
        if( empty($conversations) )
        {
            $conversations = $this->conversationRepo->findByUserQuery($user->getId())->getResult();
        }

        // dump($conversations);die;

        return $this->render('conversation/index.html.twig', [
            'conversations'       => $conversations,
            'conversationEncours' => $conversationEncours,
            'messages'            => $messages,                          // Messages de la conversation en cours
            'annonce'             => $conversationEncours->getAnnonce(), // Annonce raccorder à la conversation en cours
            'destinataire'        => $destinataire                       // Déstinataire des messages de la conversation en cours
        ]);
    }
}
