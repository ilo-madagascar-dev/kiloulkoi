<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
     /**
     * @Route("/inscriptionUtilisateur", name="security_registrationlog")
     */
    public function registration(Request $request, EntityManagerInterface $em,
    UserPasswordEncoderInterface $encoder)
 
    {
      $user = new User();

      $form = $this->createForm(RegistrationType::class, $user);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
          // l'objet $em sera affecté automatiquement grâce à l'injection des dépednaces 
          $em->persist($user);
          $em->flush();
      }
      return $this->render('security/registration.html.twig', ['form' =>$form->createView()]);
    }

         	/**
	* @Route("/login", name="securitylogin", methods="GET|POST")
	*/
	public function login(Request $request,AuthenticationUtils $authenticationUtils)
	{	
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		
   		
		return $this->render('security/login.html.twig',[
			'error' => $error,
			'lastUsername' => $lastUsername
			
		]);
	}
}
