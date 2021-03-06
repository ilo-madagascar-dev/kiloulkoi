<?php

namespace App\Controller\Conversation;

use App\Entity\Location;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\LocationRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/conversation")
 */
class ConversationController extends AbstractController
{
    private $conversationRepo;
    private $messageRepo;
    private $locationsRepository;

    public function __construct(ConversationRepository $conversationRepository, MessageRepository $messageRepository, LocationRepository $locationsRepository)
    {
        $this->conversationRepo = $conversationRepository;
        $this->messageRepo      = $messageRepository;
        $this->locationsRepository = $locationsRepository;
    }

    /**
     * @Route("/", name="conversation_index", methods={"get"})
     */
    public function index()
    {
        $user          = $this->getUser();
        $conversations = $this->conversationRepo->findByUserQuery($user->getId())->getResult();

        if (count($conversations) > 0) {
            /**
             * @var(Conversation $conversationEncours)
             */
            $conversationEncours = $conversations[0];
            return $this->showConversation($conversationEncours, $conversations);
        } else {
            return $this->render('conversation/erreur.html.twig', [
                'error' => "Vous n'avez aucune conversation!"
            ]);
        }
    }

    /**
     * @Route("/messages/{location}/{destinataire}", name="conversation_messages_new", methods={"post"})
     */
    public function nouveauMessage(Location $location, User $destinataire, Request $request, PublisherInterface $publisher, SerializerInterface $serializer)
    {
        $expediteur = $this->getUser();
        if ($destinataire->getId() == $expediteur->getId()) {
            return $this->render('conversation/erreur.html.twig', [
                'error' => "Vous ne pouvez pas envoyer un message ?? vous m??me!"
            ]);
        }

        $message      = new Message();
        $conversation = $this->conversationRepo->findOneWith($destinataire->getId(), $expediteur->getId());
        if ($conversation == null) {
            $conversation = new Conversation();

            $conversation->setUser1($expediteur);
            $conversation->setUser2($destinataire);
            $conversation->addLocation($location);
        } else {
            if (!$conversation->getLocations()->contains($location)) {
                $conversation->addLocation($location);
            }
        }

        // mark conversation as read by the sender and unread by the receiver
        if ($conversation->getUser1()->getId() == $expediteur->getId()) {
            $conversation->setLu1(true);
            $conversation->setLu2(false);
        } else {
            $conversation->setLu1(false);
            $conversation->setLu2(true);
        }

        $message->setConversation($conversation);
        $message->setUser($expediteur);
        $message->setContenue($request->get("contenue"));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($conversation);
        $entityManager->persist($message);
        $entityManager->flush();

        $conversation->getMessages()->add($message);
        $serialized = $serializer->serialize([
            'user' => [
                'id'       => $expediteur->getId(),
                'avatar'   =>  '/uploads/avatar/' . $expediteur->getAvatar(),
                'fullName' => $expediteur->getNomComplet()
            ],
            'content' => $message->getContenue(),
            'date'    => $message->getDate()->format('d/m/Y | H:m'),
            'conversation' => $conversation->getId(),
            'path'    => $this->generateUrl('conversation_show', ['id' => $conversation->getId()]),
            'unread'  => $this->conversationRepo->countUnreadBy($destinataire),
            'type'    => 'message',
        ], 'json');

        $publisher(new Update(
            [$_ENV['KILOUKOI_S_MERCURE_SUBSCRIBER_URL'] . $destinataire->getId()],
            $serialized,
            true,
        ));

        if ($request->isXmlHttpRequest()) {
            return new Response($serialized);
        } else {
            return $this->redirectToRoute('conversation_show', ['id' => $conversation->getId()]);
        }
    }

    /**
     * @Route("/get-unread", name="conversation_unread", methods={"get"})
     */
    public function getUnreadMessages(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $unread = $this->conversationRepo->countUnreadBy($this->getUser());
            return new Response(json_encode(['unread' => $unread]));
        } else {
            return new Response(null);
        }
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
        if ($this->isCsrfTokenValid('delete' . $conversation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($conversation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conversation_index');
    }

    private function showConversation(Conversation $conversationEncours, array $conversations = []): Response
    {
        $user         = $this->getUser();
        $messages     = $this->messageRepo->findByConversation($conversationEncours->getId())->getResult();
        $destinataire = ($user->getId() == $conversationEncours->getUser1()->getId()) ? $conversationEncours->getUser2() : $conversationEncours->getUser1();
        $mercureHubUrlDeux = $_ENV['KILOUKOI_MERCURE_HUB_URL_TWO'];
        $mercureGeneralSubscriberUrl = $_ENV['MERCURE_GENERAL_PUBLISH_URL'];

        if (empty($conversations)) {
            $conversations = $this->conversationRepo->findByUserQuery($user->getId())->getResult();
        }

        // mark conversation as read if it is not
        if ($conversationEncours->getUser1()->getId() == $user->getId() && !$conversationEncours->getLu1()) {
            $conversationEncours->setLu1(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($conversationEncours);
            $entityManager->flush();
        } else if ($conversationEncours->getUser2()->getId() == $user->getId() && !$conversationEncours->getLu2()) {
            $conversationEncours->setLu2(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($conversationEncours);
            $entityManager->flush();
        }

        return $this->render('conversation/index.html.twig', [
            'conversations'       => $conversations,
            'conversationEncours' => $conversationEncours,
            'messages'            => $messages,                               // Messages de la conversation en cours
            'location'            => $conversationEncours->getLocations()[0], // Location raccorder ?? la conversation en cours
            'locations'            => $conversationEncours->getLocations(), // Location raccorder ?? la conversation en cours
            'destinataire'        => $destinataire,                            // D??stinataire des messages de la conversation en cours
            'mercureHubUrlDeux' => $mercureHubUrlDeux,
            'mercureGeneralSubscriberUrl' => $mercureGeneralSubscriberUrl
        ]);
    }
}
