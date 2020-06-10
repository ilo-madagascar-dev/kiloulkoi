<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Controller\MailerController;

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
	public function verification(Request $request,UserRepository $repositoryuser,MailerController $mailmdp): Response
	{	
        

        $users = $repositoryuser->findAllUsers($request->get('username'));

        if (!$users) 
        {
            $this->addFlash('invalideMDP','Votre address email est invalide');
            throw new AccessDeniedException('Votre address email est invalide');
        }

        $username;$password;$fonction;
        foreach ($users as $listusers) 
        {
           $listusers->setUsername($request->get('username'));
           $listusers->setPassword(($request->get('password')+random_int(100, 900)));
           $username = $listusers->getUsername($request->get('username'));
           $password = $listusers->getPassword($request->get('password'));
           $fonction = $listusers->getFonction($request->get('fonction'));
        }
        
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setFonction($fonction);

        $mailmdp->sendEmailMdp($user);
        /*$this->em->persist($user);*/
        $this->em->flush();
        $this->addFlash('invalideMDP','Modification mot de passe , veulliez verifier votre email');
        
        /*Dump($password);*/


        // return $this->render('security/login.html.twig');
        return $this->redirectToRoute('securitylogin');
		
	}
}
