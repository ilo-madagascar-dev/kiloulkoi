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
use App\Repository\ProfessionnelRepository;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\FileUploader;
use App\Service\MangoPayService;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

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
    public function register(Request $request, string $type, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator, FileUploader $uploader, MangoPayService $mangoPayService): Response
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
            //Vérification du captcha
            $response = false;

            if (!empty($request->request->get('g-recaptcha-response'))) { 
                $response = true;
            }

            if($response !== true){
                $this->addFlash('danger', 'Recaptcha invalide');
                return $this->redirectToRoute('app_register', ['type' => $type]);
            }

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
                    $user->setAvatar('default-men.png');
                }
                else
                {
                    $user->setAvatar('default-women.png');
                }
            }

            $user->setDateCreation();
            $user->setDateMiseAJour();
            $user->setActif(true);

            if( $type == 'particulier' )
            {
                $nom        = $user->getNom();
                $prenom     = $user->getPrenom();
                $addr       = $user->getAdresse();
                $city       = $user->getVille();
                $region     = $request->get('region');
                $postalCode = $user->getCp();
                $birthday   = intval(strtotime($request->get('datenaissance')));
                $nationality = "FR";
                $countryOfResidence = "FR";
               

                $mangoPayUserId = $mangoPayService->createUserParticulier($user->getEmail(), $nom, $prenom, $addr, $city, $region, $postalCode, $birthday, $nationality, $countryOfResidence);
            }
            else
            {
                
                $address = $user->getAdresse();
                $city = $user->getVille();
                $region = $request->get('region');
                $postalCode = $user->getCp();
                $legalPersonType = $request->get('legalType');
                $name = $user->getRaisonSocial();
                $birthday = intval(strtotime($request->get('datenaissance')));
                $countryOfResidence = "FR";
                $email = $user->getEmail();
                $firstName = $user->getPseudo();
                $lastName = $user->getPseudo();
                $companyNumber = $user->getSiret();
                $addrEntreprise = $request->get('addressEntreprise');
                $cityEntreprise = $request->get('villeEntreprise');
                $regionEntreprise = $request->get('regionEntreprise');
                $pcEntreprise = $request->get('codepoEntreprise');
                $emailEntreprise = $request->get('mailEntreprise');

                $mangoPayUserId = $mangoPayService->createUserProfessionnel($address, $city, $region, $postalCode , $legalPersonType , $name, $birthday , $countryOfResidence , $email, $firstName, $lastName,$companyNumber, $addrEntreprise, $cityEntreprise, $regionEntreprise, $pcEntreprise, $emailEntreprise);


            }

            //$mangoPayUserId = $mangoPayService->setUserMangoPay($user->getEmail(), $nom, $prenom);
            //$mangoPayService->setUserMangoPayKYC($mangoPayUserId);

            $user->setMangoPayId( $mangoPayUserId );

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
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        dd( 'lochou' );
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/checkSiret", name="security_check_siret")
     */
    public function checkSiret(Request $request, ProfessionnelRepository $userRepository)
    {
        $siret = $request->request->get('data');
        $user = $userRepository->findBy(['siret' => $siret]);

        if( $user )
            return new Response('1');
        else
            return new Response('0');
    }

    /**
     * @Route("/checkEmail", name="security_check_email")
     */
    public function checkEmail(Request $request, UserRepository $userRepository)
    {
        $email = $request->request->get('data');
        $user = $userRepository->findBy(['email' => $email]);

        if( $user )
            return new Response('1');
        else
            return new Response('0');
    }

    /**
     * @Route("/creation-temporaire-admin", name="creation_temporaire_admin")
     */
    public function creationTemporaireDadmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $uploader, MangoPayService $mangoPayService, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator){
        $user = new Particulier();
        $form = $this->createForm(ParticulierType::class, $user);

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
                    $user->setAvatar('default-men.png');
                }
                else
                {
                    $user->setAvatar('default-women.png');
                }
            }

            $user->setDateCreation();
            $user->setDateMiseAJour();
            $user->setActif(true);
            
            //Rôle de l'admin
            $role = ['ROLE_ADMIN'];
            
            $user->setRoles($role);

            //Organisation des différentes informations
                $nom        = $user->getNom();
                $prenom     = $user->getPrenom();
                $addr       = $user->getAdresse();
                $city       = $user->getVille();
                $region     = $request->get('region');
                $postalCode = $user->getCp();
                $birthday   = intval(strtotime($request->get('datenaissance')));
                $nationality = "FR";
                $countryOfResidence = "FR";
               

                $mangoPayUserId = $mangoPayService->createUserParticulier($user->getEmail(), $nom, $prenom, $addr, $city, $region, $postalCode, $birthday, $nationality, $countryOfResidence);

            //$mangoPayUserId = $mangoPayService->setUserMangoPay($user->getEmail(), $nom, $prenom);
            //$mangoPayService->setUserMangoPayKYC($mangoPayUserId);

            $user->setMangoPayId( $mangoPayUserId );

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

        return $this->render('security/adminExample.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
