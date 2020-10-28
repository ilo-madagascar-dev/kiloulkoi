<?php

namespace App\Controller\Paiement;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use MangoPay;

/**
 * @Route("/paiement")
 */
class PaiementController extends AbstractController
{

	private $mangoPayApi;

    public function __construct()
    {
        $this->mangoPayApi = new MangoPay\MangoPayApi();
        $this->mangoPayApi->Config->ClientId = 'admin-kiloukoi';
        $this->mangoPayApi->Config->ClientPassword = 'Azertyqsdf1234$';
        $this->mangoPayApi->Config->TemporaryFolder = '../../some/pathfolder/';    
        $this->mangoPayApi->Config->BaseUrl = 'https://api.sandbox.mangopay.com';
    }

    /**
     * Create Mangopay User
     * @return MangopPayUser $mangoUser
     */
    public function getMangoUser()
    {
        
        $mangoUser = new \MangoPay\UserNatural();
        $mangoUser->PersonType = "NATURAL";
        $mangoUser->FirstName = 'John';
        $mangoUser->LastName = 'Doe';
        $mangoUser->Birthday = 1409735187;
        $mangoUser->Nationality = "FR";
        $mangoUser->CountryOfResidence = "FR";
        $mangoUser->Email = 'john.doe@mail.com';

        //Send the request
        $mangoUser = $this->mangoPayApi->Users->Create($mangoUser);

        return $mangoUser;
    }

    /**
     * @Route("/", name="paiement_index")
     */
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig');
    }

    /**
     * @Route("/utilisateurs", name="paiement_utilisateurs")
     */
    public function newUser(): Response
    {
        $this->getMangoUser();

        return $this->render('paiement/index.html.twig');
    }

    
    
}
