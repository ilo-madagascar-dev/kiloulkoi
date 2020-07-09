<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class VerificationController extends AbstractController
{
    // /**
    //  * @Route("/verification", name="verification")
    //  */
    // public function index()
    // {
    //     return $this->render('verification/index.html.twig', [
    //         'controller_name' => 'VerificationController',
    //     ]);
    // }

      /**
     * @var UserRepository
     */
    private $repositoryuser;

    
    /**
     * @var EntityManagerInterface
     */
    private $em;

	public function __construct(UserRepository $repositoryuser,EntityManagerInterface $em)
    {
        $this->repositoryuser = $repositoryuser;
        $this->em = $em;
    }

	

	/**
	* @Route("/login/verification", name="verify_mdp", methods="GET|POST")
	*/
    public function verification(Request $request,UserRepository $repositoryuser,MailerController $mailmdp,
    UserPasswordEncoderInterface $encoder): Response
	{	
        

        $users = $repositoryuser->findAllUsers($request->get('username'));
        // dd($users[0]);

        if (!$users) 
        {
            $this->addFlash('invalideMDP','Votre address email est invalide');
            throw new AccessDeniedException('Votre address email est invalide');
        }

        $username = null; $password = null; $fonction = null; $confirm_password = null;
        // $nom;$prenom;$pseudo;$rue;$ville;$telephone;$cp;
        foreach ($users as $listusers) 
        {
        //    $hash = $encoder->encodePassword($listusers, $listusers->getPassword());
           $listusers->setUsername($request->get('username'));
           $listusers->setPassword(($request->get('password')+random_int(10000000, 9000000000)));
           $username = $listusers->getUsername($request->get('username'));
           $password = $listusers->getPassword($request->get('password'));
           $confirm_password = $listusers->getConfirmPassword($request->get('password'));
           $fonction = $listusers->getFonction($request->get('fonction'));
           $nom = $listusers->getNom($request->get('nom'));
           $prenom = $listusers->getPrenom($request->get('prenom'));
           $rue = $listusers->getRue($request->get('rue'));
           $ville = $listusers->getVille($request->get('ville'));
           $telephone = $listusers->getTelephone($request->get('telephone'));
           $pseudo = $listusers->getPseudo($request->get('pseudo'));
           $cp = $listusers->getCp($request->get('cp'));
        }
        
        $user = $users[0];
        $user->setUsername($username);
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $user->setConfirmPassword($confirm_password);
        $user->setFonction($fonction);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setRue($rue);
        $user->setPseudo($pseudo);
        $user->setVille($ville);
        $user->setCp($cp);
        $user->setTelephone($telephone);
        
    

        $mailmdp->sendEmailMdp($user);
        $user->setPassword($hash);
        $this->em->persist($user);
        $this->em->flush();
        $this->addFlash('invalideMDP','Modification mot de passe , veulliez verifier votre email');
        
        /*Dump($password);*/


        // return $this->render('security/login.html.twig');
        return $this->redirectToRoute('securitylogin');
		
	}
}
