<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\ConversationRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MercureCookieGenerator;

/**
* @Route("/notifications")
*/
class InitialisationController extends AbstractController
{
   	private $cookieGenerator;

	public function __construct(MercureCookieGenerator $cookieGenerator)
	{
		$this->cookieGenerator = $cookieGenerator;
	}

	/**
	 * @Route("/init-all", name="notification_init")
	 */
	public function init(Request $request, ConversationRepository $conversationRepository, NotificationRepository $notificationRepository): Response
	{
		$response = new Response();

		if( $request->isXmlHttpRequest() )
		{
			$user = $this->getUser();

			if( !$request->cookies->has('mercureAuthorization') )
			{
				$response->headers->set( 'set-cookie', $this->cookieGenerator->generate($user) );
			}

			$data = json_encode([
				'messages'      => [
					'unread' => intval($conversationRepository->countUnreadBy($user)),
				],
				'notifications' => [
					'unread' => intval($notificationRepository->countUnread($user)),
					'all'    => $this->serialize( $notificationRepository->findAllByUser($user) )
				],
			]);

			$response->setContent( $data );
			return $response;
		}
		else
		{
			return new Response(null);
		}
	}

	
	/**
	 * show
	 *
	 * @param  Notification $notification
	 * @return Response
	 * 
	 * @Route("/voir/{notification}", name="notification_redirect")
	 * 
	 */
	public function show(Notification $notification ): Response
	{
		$notification->setLu(1);
		
		$this->getDoctrine()->getManager()->persist($notification);
		$this->getDoctrine()->getManager()->flush();
		
		return $this->redirect( $notification->getRoute() );
	}

	/**
	 * serialize
	 *
	 * @param  Notification[] $notifications
	 * @return array
	 */
	private function serialize(array $notifications): array
	{
		$result = [];

		foreach( $notifications as $notification )
		{
			$result[] = [
				'content' => $notification->getMessage(),
				'date'    => $notification->getDateCreation()->format('d/m/Y | H:m'),
				'lien'    => $notification->getRoute(),
				'photo'   => $notification->getPhoto(),
				'id'      => $notification->getId(),
			];
		}

		return $result;
	}
}