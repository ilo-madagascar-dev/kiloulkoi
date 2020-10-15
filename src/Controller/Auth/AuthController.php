<?php

namespace App\Controller\Auth;

use App\Entity\Particulier;
use App\Entity\Professionnel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use App\Entity\User;
use App\Form\ParticulierType;
use App\Form\ProfessionnelType;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use App\Service\FileUploader;

class AuthController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/connexion", name="securitylogin")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('accueil');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/inscription/{type}", name="app_register")
     */
    public function register(Request $request, string $type, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator, FileUploader $uploader): Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('accueil');
        }

        if( $type == 'particulier' )
        {
            $user = new Particulier();
            $form = $this->createForm(ParticulierType::class, $user);
        }
        else
        {
            $user = new Professionnel();
            $form = $this->createForm(ProfessionnelType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() )
        {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $uploader->setTargetDirectory($this->getParameter('avatar_directory'));

            $avatar_file = $form->get('avatar')->getData();
            if( $avatar_file )
            {
                $avatar_url  = $uploader->upload($avatar_file);
                $user->setAvatar($avatar_url);
            }
            else
            {
                if( $user->getGenre() == 1 )
                {
                    $user->setAvatar('default.png');
                }
                else
                {
                    $user->setAvatar('default-women.png');
                }
            }

            $user->setDateCreation();
            $user->setDateMiseAJour();
            $user->setActif(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
