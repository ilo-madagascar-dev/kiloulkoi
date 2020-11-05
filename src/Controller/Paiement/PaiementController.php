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
        $this->mangoPayApi->Config->ClientPassword = 'MNHcmbW6FE5XMeG1M6KgzHZXfAUdAJdeZjmoNDOAQAoi6spMqF';
        $this->mangoPayApi->Config->TemporaryFolder = '/Temp/tmp';    
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
        $mangoUser->FirstName = 'nouveauuser';
        $mangoUser->LastName = 'nouveauuser';
        $mangoUser->Birthday = 1409735187;
        $mangoUser->Nationality = "FR";
        $mangoUser->CountryOfResidence = "FR";
        $mangoUser->Email = 'nouveauuser.doe@mail.com';

        
        $mangoUser = $this->mangoPayApi->Users->Create($mangoUser);

        $Wallet = new \MangoPay\Wallet();
        $Wallet->Owners = array($mangoUser->Id);
        $Wallet->Description = "Demo wallet for User 1";
        $Wallet->Currency = "EUR";
        $result = $this->mangoPayApi->Wallets->Create($Wallet);

        return $mangoUser;
    }

    /**
     * Create Mangopay User
     * @returnMangopPayUser $mangoUser walet
     */
    /*public function CreatWaletMangoUser()
    {
        
       $Wallet = new \MangoPay\Wallet();
        $Wallet->Owners = array($_SESSION["UserNatural"]);
        $Wallet->Description = "Demo wallet for User 1";
        $Wallet->Currency = "EUR";
        $result = $mangoPayApi->Wallets->Create($Wallet);

        return $result;
    }*/



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
