<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
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
	public function setMercureCookies(Request $request, ConversationRepository $conversationRepository): Response
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
					'unread' => $conversationRepository->countUnreadBy($user),
				],
				'notifications' => [
					'unread' => 22,
					'all'    => [],
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
}