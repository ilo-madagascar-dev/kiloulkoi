<?php

namespace App\Service;

use App\Entity\User;
use MangoPay;
use Symfony\Component\HttpKernel\KernelInterface;

class MangoPayService
{
	
	public function __construct(KernelInterface $kernel)
	{
		$this->rootDirectory = $kernel->getProjectDir();
	}
	
	public function getMangoPayApi()
	{
		$mangoPayApi = new MangoPay\MangoPayApi();
		
		$mangoPayApi->Config->ClientId = 'admin-kiloukoi';
		$mangoPayApi->Config->ClientPassword = 'MNHcmbW6FE5XMeG1M6KgzHZXfAUdAJdeZjmoNDOAQAoi6spMqF';
		$mangoPayApi->Config->TemporaryFolder = $this->rootDirectory . '/var/mangopay';    
		$mangoPayApi->Config->BaseUrl = 'https://api.sandbox.mangopay.com';
		
		return $mangoPayApi;
	}
	
	public function setUserMangoPay(string $email, string $nom, string $prenom): int
	{
		$mangoPayApi = $this->getMangoPayApi();
		
		$mangoUser = new \MangoPay\UserNatural();
		
		$mangoUser->Email              = $email;
		$mangoUser->PersonType         = "NATURAL";
		$mangoUser->FirstName          = $prenom;
		$mangoUser->LastName           = $nom;
		$mangoUser->Birthday           = 1409735187;
		$mangoUser->Nationality        = "FR";
		$mangoUser->CountryOfResidence = "FR";
		
		$mangoUser = $mangoPayApi->Users->Create($mangoUser);

		$Wallet = new \MangoPay\Wallet();
		$Wallet->Owners = array($mangoUser->Id);
		$Wallet->Description = "Wallet for " . $nom . ' ' . $prenom;
		$Wallet->Currency = "EUR";

		$mangoPayApi->Wallets->Create($Wallet);

		return $mangoUser->Id ;
	}

	public function setUserMangoPayKYC(string $idUser,$upf)
	{
	
		$mangoPayApi = $this->getMangoPayApi();


			$result3;
			foreach ($upf as $key => $value) {
				$UserId = $idUser;
				$upfile = $upf;/*$this->rootDirectory . '/var/mangopay/FLYER_FR.pdf';*/
				$KycDocument = new \MangoPay\KycDocument();
				$KycDocument->Type = "IDENTITY_PROOF";
				$Result = $mangoPayApi->Users->CreateKycDocument($UserId, $KycDocument);
				
				
				$KycDocumentId = $Result->Id;
				
				//create pages kyc
				$mangoPayApi->Users->CreateKycPageFromFile($UserId, $KycDocumentId, $upf[$key]);
				//submit all pages kyc
				$KycDocument = new MangoPay\KycDocument();
				$KycDocument->Id = $KycDocumentId;
				$KycDocument->Status = "VALIDATION_ASKED";
				$result3 = $mangoPayApi->Users->UpdateKycDocument($UserId, $KycDocument);
			
			}
			
			
		return $result3;
	}

	public function creatKYCPage(string $idUser,string $KYCDocId, string $upfile)
	{
		$mangoPayApi = $this->getMangoPayApi();


		try {
			$UserId = $idUser;
			$KYCDocumentId = $KYCDocId;
			$KycPage = new \MangoPay\KycPage();
			
			$KycPage->File = $upfile;
			dd($KycPage->File);
			/*base64_encode (file_get_contents($this->rootDirectory . '/var/mangopay/FLYER_FR.pdf'));*/
			
			$var = [];
			$var[0] = $UserId;
			$var[1] = $KYCDocumentId;
			$var[2] = $KycPage->File;
			/*dd($var);*/
			$Result = $mangoPayApi->Users->CreateKycPageFromFile($UserId, $KYCDocumentId, $KycPage);
			dd($Result);
	
		} catch(MangoPay\Libraries\ResponseException $e) {
			return $e->GetErrorDetails(); 
		} catch(MangoPay\Libraries\Exception $e) {
			return $e->GetMessage(); 
		}
		return $Result;
	}

	public function getKYCDocs(string $idUser)
	{
		$mangoPayApi = $this->getMangoPayApi();
		
		$kyc = $mangoPayApi->Users->GetKycDocuments($idUser);
		return $kyc;
	}

	public function getUser(string $idUser)
	{
		$mangoPayApi = $this->getMangoPayApi();
		
		$users = $mangoPayApi->Users->Get($idUser);
		return $users;
	}

	public function getWalletId(string $userMangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
        
            $waletUserId = $mangoPayApi->Users->GetWallets($userMangoId);
            $walletId;
            foreach ($waletUserId as $value) {
                $walletId = $value->Id;
            }
        return $walletId;
	}

	public function getWallet(string $userMangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$wallet = $mangoPayApi->Users->GetWallets($userMangoId);

		return $wallet;
	}

	public function Payin (string $locatairemangoId, int $feesAmount,string $currency,int $debitedFundsAmount)
	{
		$mangoPayApi = $this->getMangoPayApi();

		//get walet
		$walletId = $this->getWalletId($locatairemangoId);

		//payin processe
		$payIn = new \MangoPay\PayIn();

        	$payIn->CreditedWalletId = $walletId;
            $payIn->AuthorId         = $locatairemangoId;
            $payIn->DebitedFunds     = new \MangoPay\Money();
            $payIn->Fees             = new \MangoPay\Money();
            $payIn->Fees->Amount     = $feesAmount;
            $payIn->Fees->Currency   = $currency;
            $payIn->DebitedFunds->Amount   = $debitedFundsAmount;
            $payIn->DebitedFunds->Currency = $currency;

        //get card 
        list ($cardId, $cardType , $cardObjet) = $this->getCards($locatairemangoId);

            // payment type as CARD
            $payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
            $payIn->PaymentDetails->CardType = $cardType;
            $payIn->PaymentDetails->CardId = $cardId;
            
            // execution type as DIRECT
            $payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
            $payIn->ExecutionDetails->SecureModeReturnURL = 'http://test.com';

            // create Pay-In
            $createdPayIn = $mangoPayApi->PayIns->Create($payIn);

         return $createdPayIn->Status;

	}

	public function getCards(string $locatairemangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$cards  = $mangoPayApi->Users->GetCards($locatairemangoId);
            $cardId;
            $cardType;
            foreach ($cards as $value) {
                $cardId = $value->Id;
                $cardType = $value->CardType;
            }
        return array ($cardId, $cardType, $cards);
	}

	public function getCard(string $locatairemangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$cards  = $mangoPayApi->Users->GetCards($locatairemangoId);
            
        return $cards;
	}

	public function doTransferWalet(string $authorId, string $currency, int $debitedFundsAmount , int $transferFeesAmount , string $debitedWalletId , string $creditedWalletId )
	{
		$mangoPayApi = $this->getMangoPayApi();
		$Transfer = new \MangoPay\Transfer();
                $Transfer->AuthorId = $authorId;
                $Transfer->DebitedFunds = new \MangoPay\Money();
                $Transfer->DebitedFunds->Currency = $currency;
                $Transfer->DebitedFunds->Amount = $debitedFundsAmount;
                $Transfer->Fees = new \MangoPay\Money();
                $Transfer->Fees->Currency = $currency;
                $Transfer->Fees->Amount = $transferFeesAmount;
                $Transfer->DebitedWalletID = $debitedWalletId;
                $Transfer->CreditedWalletId = $creditedWalletId;
                $result = $mangoPayApi->Transfers->Create($Transfer);
        return $result;
	}

	public function creatCardRegistration(string $userId , string $currency , string $cardType)
	{
		$mangoPayApi = $this->getMangoPayApi();

		$cardRegister = new \MangoPay\CardRegistration();
        $cardRegister->UserId = $userId;
        $cardRegister->Currency = $currency;
        $cardRegister->CardType = $cardType;
        $createdCardRegister = $mangoPayApi->CardRegistrations->Create($cardRegister);

        return $createdCardRegister;
	}

	public function getCrdWithId(string $cardId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$cardRegister = $mangoPayApi->CardRegistrations->Get($cardId);

		return $cardRegister;
	}

	public function updateCardRegister($cardRegister)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$updatedCardRegister = $mangoPayApi->CardRegistrations->Update($cardRegister);

		return $updatedCardRegister;
	}

	public function getBankCountUser(string $userMangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$bankUser = $mangoPayApi->Users->GetBankAccounts($userMangoId);
		return $bankUser;

	}

	public function doPayoutIBAN (string $authorId,string $debitedWalletID,string $currency, int $amountDebited,int $amountFees,string $paymentType, string $bankAccountId )
	{
		$mangoPayApi = $this->getMangoPayApi();

		$PayOut = new \MangoPay\PayOut();
		$PayOut->AuthorId = $authorId;
		$PayOut->DebitedWalletID = $debitedWalletID;
		$PayOut->DebitedFunds = new \MangoPay\Money();
		$PayOut->DebitedFunds->Currency = $currency;
		$PayOut->DebitedFunds->Amount = $amountDebited;
		$PayOut->Fees = new \MangoPay\Money();
		$PayOut->Fees->Currency = $currency;
		$PayOut->Fees->Amount = $amountFees;
		$PayOut->PaymentType = $paymentType;
		$PayOut->MeanOfPaymentDetails = new \MangoPay\PayOutPaymentDetailsBankWire();
		$PayOut->MeanOfPaymentDetails->BankAccountId = $bankAccountId;
		$result = $mangoPayApi->PayOuts->Create($PayOut);

		return $result;
	}

	public function getTransactionUser (string $userMangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$transactionUser = $mangoPayApi->Users->GetTransactions($userMangoId);
		return $transactionUser;
	}

	public function getUserMango (string $userMangoId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$User = $mangoPayApi->Users->Get($userMangoId);
		return $User;
	}
	
}