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

class VerfRoleController extends AbstractController
{
    /**
     * @Route("/verf/role", name="verf_role")
     */
    public function index()
    {
        return $this->render('verf_role/index.html.twig', [
            'controller_name' => 'VerfRoleController',
        ]);
    }

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
	* @Route("/welcom", name="verify_login", methods="GET|POST")
	*/
	public function verification(Request $request,UserRepository $repositoryuser): Response
	{	
        
		$userconnect=$this->getUser()->getUsername();
        $role='ROLE_PRESTATAIRE';
        $roleclient='ROLE_CLIENT';
        $roleadmin='ROLE_ADMIN';
       
        $repositoryuser->findAllisPrestataire($userconnect,$role);
        $repositoryuser->findAllisClients($userconnect,$roleclient);
        $repositoryuser->findAllisClients($userconnect,$roleadmin);
        
        /*if users->getUsername= userconect and */
        Dump($userconnect);

        if ($repositoryuser->findAllisPrestataire($userconnect, $role)){
            return $this->redirectToRoute('menupresta');
        }else if($repositoryuser->findAllisClients($userconnect,$roleclient)){
            return $this->redirectToRoute('menu');
        } else if($repositoryuser->findAllisAdmin($userconnect,$roleadmin)){
            return $this->redirectToRoute('menuadmin');
        }
        else{
            return new Response('auccun compte , inconnue');
        }
		
		
	}
}
