<?php

namespace App\Controller\Auth;

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
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
Use KnpU\OAuth2ClientBundle\Exception\InvalidStateException;
use League\OAuth2\Client\Provider\GoogleUser;

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
     * @Route("/connect/google", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
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
        FileUploader $uploader
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
            $user = new User();
            
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
