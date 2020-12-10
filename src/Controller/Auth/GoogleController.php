<?php

namespace App\Controller\Auth;

use App\Entity\Particulier;
use App\Entity\Professionnel;
use App\Entity\User;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\UserAuthenticator;
use App\Service\FileUploader;
use App\Service\MangoPayService;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
Use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GoogleController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google/", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(Request $request, ClientRegistry $clientRegistry, SessionInterface $session)
    {
        $type = $request->get('type');
        if( $type == 'professionnel' )
        {
            $session->set('userType', 'professionnel');
        }
        else
        {
            $session->set('userType', 'particulier');
        }

        return $clientRegistry->getClient('google')
                              ->redirect();
    }

    /**
     * Google redirects to back here afterwards
     *
     * @Route("/connect/google/check", name="connect_google_check")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectCheckAction(
        Request $request, 
        ClientRegistry $clientRegistry, 
        UserPasswordEncoderInterface $passwordEncoder, 
        GuardAuthenticatorHandler $guardHandler, 
        UserAuthenticator $authenticator,
        FileUploader $uploader, 
        SessionInterface $session,
        MangoPayService $mangoPayService
    ) 
    {
        $client = $clientRegistry->getClient('google');

        try {
            /**
             * var League\OAuth2\Client\Provider\GoogleUser
             */
            $googleUser = $client->fetchUser();
        } 
        catch (IdentityProviderException | InvalidStateException $e)
        {            
            return $this->redirectToRoute('securitylogin');
        }
        catch(Exception $e)
        {
            return $this->redirectToRoute('securitylogin');
        }
    
        $em   = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')
                   ->findOneBy(['email' => $googleUser->getEmail()]);
        
        if( !$user ) 
        {
            if( $session->get('userType') == 'professionnel' )
            {
                $user = new Professionnel();
                $user->setRaisonSocial($googleUser->getName() . ' - ' . $googleUser->getFirstName());
                $user->setSiret('00000000000000');
            }
            else
            {
                $user = new Particulier();
                $user->setNom($googleUser->getName());
                $user->setPrenom($googleUser->getFirstName());
            }
            
            $directory   = $this->getParameter('avatar_directory') . '/';
            $avatar_url  = md5(uniqid()) . '.png';

            $avatar_file = file_get_contents($googleUser->getAvatar());
            $avatar_file = file_put_contents($directory . $avatar_url, $avatar_file);

            $nom         = str_replace($googleUser->getFirstName(), '', $googleUser->getName());

            $user->setEmail($googleUser->getEmail());
            // $user->setNom($nom);
            // $user->setPrenom($googleUser->getFirstName());
            $user->setAvatar($avatar_url);
            $user->setDateCreation();
            $user->setDateMiseAJour();
            $user->setVille('-');
            $user->setAdresse('-');
            $user->setCp('-');
            $user->setTelephone('-');
            $user->setGenre(1);
            $user->setDateMiseAJour();
            $user->setPseudo( $googleUser->getFirstName() );
            $user->setDateMiseAJour();
            $user->setActif(true);
            
            $mangoPayUserId = $mangoPayService->setUserMangoPay($user->getEmail(), $googleUser->getName(), $googleUser->getFirstName());
            $user->setMangoPayId( $mangoPayUserId );

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $googleUser->getId()
                )
            );

            $em->persist($user);
            $em->flush();
        }

        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );
    }

}
