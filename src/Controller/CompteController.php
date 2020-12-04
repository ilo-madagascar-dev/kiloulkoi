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
       list ($cardId, $cardType , $cards) = $mangoPayService->getCards($createdCardRegister->UserId);
       
       
       $portFeuil = $waletUserId = $mangoPayService->getWallet($this->getUser()->getMangoPayId());

       $wallet = $session->get('wallet');
       
            $wallet = $portFeuil;
       
       $session->set('wallet', $wallet);

       //transaction get
       $transactionUser= $mangoPayService->getTransactionUser($this->getUser()->getMangoPayId());
       $walletSeller;$walletBuyer;$date;
       foreach ($transactionUser as $value) {
            $walletSeller = $value->CreditedWalletId;
            $walletBuyer = $value->DebitedWalletId;
            $date = $value->CreationDate;
       }

       $seller = (object)[];$buyer=  (object)[];;
       if ($walletSeller && $walletBuyer) {
         $seller = $mangoPayService->getWallet($walletSeller);
         $buyer = $mangoPayService->getWallet($walletBuyer);  
       }
       
        return $this->render('compte/portefeuille.html.twig',[
            'dataform' => $arrayName,
            'cards' => $cards,
            'walet' => $portFeuil,
            'transaction' => $transactionUser,
            'seller' => $seller,
            'buyer' => $buyer,
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

        
        /*dd($request->get('data'));*/
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
     * @Route("/portefeuille/transfere", name="portefeuille_transfer")
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
        $bankAccountId;
        foreach ($bankUser as $value) {
                $bankAccountId = $value->Id;
          }

         if ($bankUser) {
             //do paying transfer
            $responseTransfer = $mangoPayService->doPayoutIBAN($this->getUser()->getMangoPayId(),$walletId,$currency,$amountDebited,0,"BANK_WIRE",$bankAccountId);
         }else{
            $this->addFlash('compteIBAN', '!Vous n avez pas enconre de compte bancaire IBAN sur votre compte mangopay.');
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
}
