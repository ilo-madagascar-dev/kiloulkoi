<?php

namespace App\Controller;

use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use MangoPay;

/**
 * @Route("/compte")
 */
class CompteController extends AbstractController
{

    private $mangoPayApi;

    public function __construct(MangoPayService $mangoPayService)
    {
        $this->mangoPayApi = $mangoPayService->getMangoPayApi();
    }

    /**
     * @Route("/facture", name="compte_facture")
     */
    public function index(): Response
    {
        return $this->render('compte/facture.html.twig', [
            'months' => $month = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre")
        ]);
    }

    /**
     * @Route("/portefeuille", name="compte_portefeuille")
     */
    public function portefeuille(SessionInterface $session): Response
    {
        $cardRegister = new \MangoPay\CardRegistration();
        $cardRegister->UserId = $this->getUser()->getMangoPayId();
        $cardRegister->Currency = "EUR";
        $cardRegister->CardType = "CB_VISA_MASTERCARD";
        $createdCardRegister = $this->mangoPayApi->CardRegistrations->Create($cardRegister);
        /*$_SESSION['cardRegisterId'] = $createdCardRegister->Id;*/
        /*$returnUrl = 'http' . ( isset($_SERVER['HTTPS']) ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'];
            $returnUrl .= substr($_SERVER['REQUEST_URI'], 0, strripos($_SERVER['REQUEST_URI'], '/') + 1);
            $returnUrl .= 'payment.php';*/
        $arrayName = array(
            'Id' => $createdCardRegister->Id,
            'UserId' => $createdCardRegister->UserId,
            'AccessKey' => $createdCardRegister->AccessKey,
            'PreregistrationData' => $createdCardRegister->PreregistrationData,
            'CardRegistrationURL' => $createdCardRegister->CardRegistrationURL,
        );
        $cardId = $session->get('cardId', []);
       
            $cardId['id'] = $createdCardRegister->Id;
       
       $session->set('cardId', $cardId);
       /*dd($createdCardRegister->Id);*/
       $cards = $this->mangoPayApi->Users->GetCards($createdCardRegister->UserId);
       $portFeuil = $waletUserId = $this->mangoPayApi->Users->GetWallets($this->getUser()->getMangoPayId());
       /*
       $CardRegistrationId = 91546698;
        $CardRegistrationget = $this->mangoPayApi->Cards->Get($createdCardRegister->Id);
       dd($CardRegistrationget);*/
       /*dd($portFeuil);*/
        return $this->render('compte/portefeuille.html.twig',[
            'dataform' => $arrayName,
            'cards' => $cards,
            'walet' => $portFeuil,
            'proprietaire' => $this->getUser()->getNomComplet()
        ]);
    }

    /**
     * @Route("/abonnement", name="compte_abonnement")
     */
    public function abonnement(): Response
    {
        return $this->render('compte/abonnement.html.twig');
    }

    /**
     * @Route("/portefeuille/card", name="portefeuille_creat")
     */
    public function portefeuilleCreat(SessionInterface $session, Request $request): Response
    {   

        
        /*dd($request->get('data'));*/
        if ($request->get('data')) {
            $cardId = $session->get('cardId', []);
       
            
            $cardRegister = $this->mangoPayApi->CardRegistrations->Get($cardId['id']);
            $cardRegister->RegistrationData = 'data=' . $request->get('data');
            $updatedCardRegister = $this->mangoPayApi->CardRegistrations->Update($cardRegister);
            if (!empty($cardId['id'])) {
            unset($cardId['id']);
            }
            return $this->redirectToRoute('compte_portefeuille');
        }else{
            dd($request->get('errorCode'));
        }
        
        /*return new Response('create card');*/
        return $this->redirectToRoute('compte_portefeuille');
    }
}
