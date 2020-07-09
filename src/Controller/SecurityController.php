<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ClientType;
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
    public function registrationClient(Request $request, EntityManagerInterface $em,
    UserPasswordEncoderInterface $encoder)
 
    {
      $user = new User();

      $form = $this->createForm(ClientType::class, $user);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setFonction('ROLE_CLIENT');
        $user->setCp('');
        $user->setVille('');
        $user->setTelephone('');
        $user->setNom('');
        $user->setRue('');
        $user->setPrenom('');
          // l'objet $em sera affecté automatiquement grâce à l'injection des dépednaces 
          $em->persist($user);
          $em->flush();
          return $this->redirectToRoute('securitylogin');
      }
      return $this->render('security/registration.html.twig', ['form' =>$form->createView()]);
    }


     /**
     * @Route("/inscriptionpresta", name="security_registrationpresta")
     */
    public function registrationPrestataire(Request $request, EntityManagerInterface $em,
    UserPasswordEncoderInterface $encoder)
 
    {
      $user = new User();

      $form = $this->createForm(RegistrationType::class, $user);

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $hash = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setFonction('ROLE_PRESTATAIRE');
          // l'objet $em sera affecté automatiquement grâce à l'injection des dépednaces 
          $em->persist($user);
          $em->flush();
          return $this->redirectToRoute('securitylogin');
      }
      return $this->render('security/prestataire.html.twig', ['form' =>$form->createView()]);
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
  
  /**
   * @Route("/deconnexion",name="security_logout")
   */

   public  function logout() {
     
   }

     /**
   * @Route("/choix",name="choix_inscrit")
   */

  public  function choix() {
     return $this->render('security/Choix.html.twig');
  }

      /**
   * @Route("/menuclient",name="menu")
   */

  public  function client() {
    return $this->render('accueil/index.html.twig');
 }

       /**
   * @Route("/menupresta",name="menupresta")
   */
  public  function presta() {
    return $this->render('accueil/prestataire.html.twig');
 }

        /**
   * @Route("/menuAdmin",name="menuadmin")
   */
  public  function admin() {
    return $this->render('admin/index.html.twig');
 }

}
