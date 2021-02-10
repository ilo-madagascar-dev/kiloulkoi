<?php

namespace App\Controller;

use App\Service\MangoPayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @Route("/compte")
 */
class CompteController extends AbstractController
{



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
    public function portefeuille(SessionInterface $session,MangoPayService $mangoPayService): Response
    {

        //listes bank acount
        $listBankAccount = $mangoPayService->viewBankAccount($this->getUser()->getMangoPayId());

        //create card registration
        $createdCardRegister = $mangoPayService->creatCardRegistration($this->getUser()->getMangoPayId(),"EUR","CB_VISA_MASTERCARD");

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


       //get card
       $cards     = $mangoPayService->getCard($this->getUser()->getMangoPayId());
       $portFeuil = $mangoPayService->getWallet($this->getUser()->getMangoPayId());
       $session->set('wallet', $portFeuil);




        return $this->render('compte/portefeuille.html.twig',[
            'dataform' => $arrayName,
            'cards' => $cards,
            'walet' => $portFeuil,
            'listBankAccount' => $listBankAccount,
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
    public function portefeuilleCreat(SessionInterface $session, Request $request,MangoPayService $mangoPayService): Response
    {


        
        if ($request->get('data')) {
            $cardId = $session->get('cardId', []);

            //
            $cardRegister = $mangoPayService->getCrdWithId($cardId['id']);

            $cardRegister->RegistrationData = 'data=' . $request->get('data');

            $mangoPayService->updateCardRegister($cardRegister);

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

    /**
     * @Route("/portefeuille/transfert", name="portefeuille_transfer")
     */
    public function transfereBank(SessionInterface $session, Request $request,MangoPayService $mangoPayService): Response
    {
        $walletUser = $mangoPayService->getWallet($this->getUser()->getMangoPayId());
        $walletId;$currency;$amountDebited;
        foreach ($walletUser as $value) {
            $walletId = $value->Id;
            $currency = $value->Currency;
            $amountDebited = $value->Balance->Amount;
        }

        // get bank count IBAN user
        $bankUser = $mangoPayService->getBankCountUser($this->getUser()->getMangoPayId());
        /*$bankAccountId;
        foreach ($bankUser as $value) {
            $bankAccountId = $value->Id;
        }*/
        $bankAccountId = $request->get('bankAccountId');

        if ($bankUser) {

            $getkycdoc   = $mangoPayService->getKYCDocs($this->getUser()->getMangoPayId());
            $usersmango  = $mangoPayService->getUser($this->getUser()->getMangoPayId());

            if ($usersmango->KYCLevel == 'REGULAR') {
               //do paying transfer
               $responseTransfer = $mangoPayService->doPayoutIBAN($this->getUser()->getMangoPayId(),$walletId,$currency,$amountDebited,0,"BANK_WIRE",$bankAccountId);
               $this->addFlash('compteIBANSuccess', 'Transfert réussi.');
            }else{
               $this->addFlash('compteIBAN', 'Vos documments ne sont pas encore valide.');
            }

        }else{
            $this->addFlash('compteIBAN', 'Vous n avez pas enconre de compte bancaire IBAN sur votre compte mangopay.');
        }

        //check transaction
        $transaction = $mangoPayService->getTransactionUser($this->getUser()->getMangoPayId());
        $typetransation;
        foreach ($transaction as $value) {
            $typetransation = $value->Type;
        }
        $type = $session->get('type');
        $type = $typetransation;
        $session->set('type', $type);

        return $this->redirectToRoute('compte_portefeuille');
    }


    /**
     * @Route("/portefeuille/payin", name="portefeuille_payin")
     */
    public function transfertPortefeuil(Request $request, MangoPayService $mangoPayService) : Response
    {
        $montant = floatval( $request->request->get('montant') ) * 100;
        $carte   = floatval( $request->request->get('carte') );

        $status = $mangoPayService->Payin($this->getUser()->getMangoPayId(), $carte, $montant);
        if( $status == \MangoPay\PayInStatus::Succeeded )
        {
            $this->addFlash('successPayin', 'Transfert éffectué');
        }
        else
        {
            $this->addFlash('errorPayin', 'Un problème est survenu, la carte est désactivé ou expirer');
        }

        return $this->redirectToRoute('compte_portefeuille');
    }

    /**
     * @Route("/portefeuille/card/update/{id}", name="card_update")
     */
    public function cardsUpdate(Request $request,MangoPayService $mangoPayService): Response
    {
        
        if ($request->get('data')) {
            
            $cardRegister = $mangoPayService->getCrdWithId('98883210');

            
            $cardRegister->RegistrationData = 'data=' . $request->get('data');

            $mangoPayService->updateCardRegister($cardRegister);

            
            return $this->redirectToRoute('compte_portefeuille');
        }else{
            dd($request->get('errorCode'));
        }

        
        return $this->redirectToRoute('compte_portefeuille');
    }


    /**
     * @Route("/portefeuille/card/statut", name="card_status")
     */
    public function cardsStatus(Request $request,MangoPayService $mangoPayService): Response
    {
        
        if ($request->get('boolean')  == "false") {
            
            $mangoPayService->statusCarte($request->get('id'),false);
            $this->addFlash('successPayin', 'La carte est désactivé');
           
        }else{
            
            $this->addFlash('errorCardActive', 'Vous ne pouvez plus réactiver la carte');
            
        }

        
        return $this->redirectToRoute('compte_portefeuille');
    }
}
