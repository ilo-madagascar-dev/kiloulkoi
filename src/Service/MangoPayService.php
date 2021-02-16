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

	public function createUserGoogle(string $email, string $nom, string $prenom): int
	{
		$mangoPayApi = $this->getMangoPayApi();

		$mangoUser = new \MangoPay\UserNatural();

		$mangoUser->Email              = $email;
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

	public function createUserParticulier(string $email, string $nom, string $prenom, string $addr, string $city,string $region, string $postalCode, int $birthday,string $nationality, string $countryOfResidence): int
	{

		try {

			$mangoPayApi = $this->getMangoPayApi();

			$UserNatural = new \MangoPay\UserNatural();
			$UserNatural->FirstName = $prenom;
			$UserNatural->LastName = $nom;
			$UserNatural->Address = new \MangoPay\Address();
			$UserNatural->Address->AddressLine1 = $addr;
			$UserNatural->Address->AddressLine2 = $addr;
			$UserNatural->Address->City = $city;
			$UserNatural->Address->Region = $region;
			$UserNatural->Address->PostalCode = $postalCode;
			$UserNatural->Address->Country = "FR";
			$UserNatural->Birthday = $birthday;
			$UserNatural->Nationality = $nationality;
			$UserNatural->CountryOfResidence = $countryOfResidence;
			$UserNatural->Email = $email;
			$UserNatural = $mangoPayApi->Users->Create($UserNatural);

			$Wallet = new \MangoPay\Wallet();
			$Wallet->Owners = array($UserNatural->Id);
			$Wallet->Description = "Wallet for " . $nom . ' ' . $prenom;
			$Wallet->Currency = "EUR";

			$mangoPayApi->Wallets->Create($Wallet);
			
		} catch(MangoPay\Libraries\ResponseException $e) {
			// handle/log the response exception with code $e->GetCode(), message $e->GetMessage() and error(s) $e->GetErrorDetails() 
		} catch(MangoPay\Libraries\Exception $e) {
			// handle/log the exception $e->GetMessage() 
		}

		return $UserNatural->Id ;
	}

	public function createUserProfessionnel(string $address, string $city, string $region, string $postalCode , string $legalPersonType , string $name, int $birthday , string $countryOfResidence , string $email, string $firstName, string $lastName,string $companyNumber,string $addrEntreprise,string $cityEntreprise,string $regionEntreprise,string $pcEntreprise,string $emailEntreprise): int
	{
	
		try {

			$mangoPayApi = $this->getMangoPayApi();

			$UserLegal = new \MangoPay\UserLegal();

			$UserLegal->HeadquartersAddress = new \MangoPay\Address();
			$UserLegal->HeadquartersAddress->AddressLine1 = $addrEntreprise;
			$UserLegal->HeadquartersAddress->AddressLine2 = $addrEntreprise;
			$UserLegal->HeadquartersAddress->City = $cityEntreprise;
			$UserLegal->HeadquartersAddress->Region = $regionEntreprise;
			$UserLegal->HeadquartersAddress->PostalCode = $pcEntreprise;
			$UserLegal->HeadquartersAddress->Country = "FR";
			$UserLegal->LegalPersonType = $legalPersonType;
			$UserLegal->Name = $name;
			$UserLegal->LegalRepresentativeAddress = new \MangoPay\Address();
			$UserLegal->LegalRepresentativeAddress->AddressLine1 = $address;
			$UserLegal->LegalRepresentativeAddress->AddressLine2 = $address;
			$UserLegal->LegalRepresentativeAddress->City = $city;
			$UserLegal->LegalRepresentativeAddress->Region = $region;
			$UserLegal->LegalRepresentativeAddress->PostalCode = $postalCode;
			$UserLegal->LegalRepresentativeAddress->Country = "FR";
			$UserLegal->LegalRepresentativeBirthday = $birthday;
			$UserLegal->LegalRepresentativeCountryOfResidence = $countryOfResidence;
			$UserLegal->LegalRepresentativeNationality = "FR";
			$UserLegal->LegalRepresentativeEmail = $email;
			$UserLegal->LegalRepresentativeFirstName = $firstName;
			$UserLegal->LegalRepresentativeLastName = $lastName;
			$UserLegal->Email = $emailEntreprise;
			$UserLegal->CompanyNumber = $companyNumber;

			$UserLegal = $mangoPayApi->Users->Create($UserLegal);

			$Wallet = new \MangoPay\Wallet();
			$Wallet->Owners = array($UserLegal->Id);
			$Wallet->Description = "Wallet for " . $name . ' ' . $firstName;
			$Wallet->Currency = "EUR";

			$mangoPayApi->Wallets->Create($Wallet);
			
		} catch(MangoPay\Libraries\ResponseException $e) {
			// handle/log the response exception with code $e->GetCode(), message $e->GetMessage() and error(s) $e->GetErrorDetails() 
		} catch(MangoPay\Libraries\Exception $e) {
			// handle/log the exception $e->GetMessage() 
		}

		return $UserLegal->Id ;
	}

	public function setUserMangoPayKYC(string $userId, $upf)
	{
        $mangoPayApi = $this->getMangoPayApi();

        try {
            // Create KYC Document with his type
            $kycDocument = new \MangoPay\KycDocument();
            $kycDocument->Type = "IDENTITY_PROOF";
            $getKycDocument = $mangoPayApi->Users->CreateKycDocument($userId, $kycDocument);

            // Add KYC Document Id and Status
            $kycDocument->Id = $getKycDocument->Id;
            $kycDocument->Status = "VALIDATION_ASKED";

            // Create KYC Page for each file to upload
            foreach ($upf as $key => $value) {
                $mangoPayApi->Users->CreateKycPageFromFile($userId, $kycDocument->Id, $upf[$key]);
            }

            // Update and Submit KYC Document
            $result = $mangoPayApi->Users->UpdateKycDocument($userId, $kycDocument);
            return $result;

        } catch(MangoPay\Libraries\ResponseException $e) {
			return $e->GetErrorDetails();
		} catch(MangoPay\Libraries\Exception $e) {
			return $e->GetMessage();
		}

        /*
        result3;
        foreach ($upf as $key => $value) {
            $UserId = $idUser;
            $upfile = $upf;//$this->rootDirectory . '/var/mangopay/FLYER_FR.pdf';
            $KycDocument = new \MangoPay\KycDocument();
            $KycDocument->Type = "IDENTITY_PROOF";
            $Result = $mangoPayApi->Users->CreateKycDocument($UserId, $KycDocument);

            $KycDocumentId = $Result->Id;

            //create pages kyc
            //$mangoPayApi->Users->CreateKycPageFromFile($UserId, $KycDocumentId, $upf[$key]);
            //submit all pages kyc
            $KycDocument = new MangoPay\KycDocument();
            $KycDocument->Id = $KycDocumentId;
            $KycDocument->Status = "VALIDATION_ASKED";
            $result3 = $mangoPayApi->Users->UpdateKycDocument($UserId, $KycDocument);

        }
		return $result3;*/
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

	public function Payin (string $locatairemangoId, string $carteId, int $debitedFundsAmount, int $feesAmount = 0, string $currency = "EUR")
	{

		//payin processe
		$payIn = new \MangoPay\PayIn();

    	$payIn->CreditedWalletId = $this->getWalletId($locatairemangoId);
        $payIn->AuthorId         = $locatairemangoId;
        $payIn->DebitedFunds     = new \MangoPay\Money();
        $payIn->Fees             = new \MangoPay\Money();
        $payIn->Fees->Amount     = $feesAmount;
        $payIn->Fees->Currency   = $currency;
        $payIn->DebitedFunds->Amount   = $debitedFundsAmount;
        $payIn->DebitedFunds->Currency = $currency;

        //get card
       	$carte = $this->getCardById($carteId);

        // payment type as CARD
        $payIn->PaymentDetails = new \MangoPay\PayInPaymentDetailsCard();
        $payIn->PaymentDetails->CardType = $carte->CardType;
        $payIn->PaymentDetails->CardId = $carteId;

        // execution type as DIRECT
        $payIn->ExecutionDetails = new \MangoPay\PayInExecutionDetailsDirect();
        $payIn->ExecutionDetails->SecureModeReturnURL = 'http://test.com';

        // create Pay-In
		$mangoPayApi  = $this->getMangoPayApi();
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

	public function doTransferWalet(string $buyer,string $seller, float $prix ,string $currency="EUR" , int $transferFeesAmount = 0)
	{
		
		$debitedFundsAmount = intval($prix * 100);

		try {

			$mangoPayApi = $this->getMangoPayApi();
			$Transfer = new \MangoPay\Transfer();
                $Transfer->AuthorId = $buyer;
                $Transfer->DebitedFunds = new \MangoPay\Money();
                $Transfer->DebitedFunds->Currency = $currency;
                $Transfer->DebitedFunds->Amount = $debitedFundsAmount;
                $Transfer->Fees = new \MangoPay\Money();
                $Transfer->Fees->Currency = $currency;
                $Transfer->Fees->Amount = $transferFeesAmount;
                $Transfer->DebitedWalletID = $this->getWalletId($buyer);
                $Transfer->CreditedWalletId = $this->getWalletId($seller);
                $result = $mangoPayApi->Transfers->Create($Transfer);
			
		} catch(MangoPay\Libraries\ResponseException $e) {
			return $result = $e->GetErrorDetails();
		} catch(MangoPay\Libraries\Exception $e) {
			return $result = $e->GetMessage();
		}
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

	// get card REGISTRATION with ID
	public function getCrdWithId(string $cardId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$cardRegister = $mangoPayApi->CardRegistrations->Get($cardId);

		return $cardRegister;
	}

	// get card with ID
	public function getCarteId(string $cardId)
	{
		$mangoPayApi = $this->getMangoPayApi();
		$cards = $mangoPayApi->Cards->Get($cardId);

		return $cards;
	}

	// active and desactive card with ID
	public function statusCarte(string $cardId, bool $boolean)
	{
		
		try {
			
			$Card = new \MangoPay\Card();
			$Card->Id = $cardId;
			$Card->Active = $boolean;
			$Result = $this->getMangoPayApi()->Cards->Update($Card);
			
			
		} catch(MangoPay\Libraries\ResponseException $e) {
			// handle/log the response exception with code $e->GetCode(), message $e->GetMessage() and error(s) $e->GetErrorDetails() 
		} catch(MangoPay\Libraries\Exception $e) {
			// handle/log the exception $e->GetMessage() 
		}

		return $Result;
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


	public function getCardById (string $CardId)
	{
		try {
			return $this->getMangoPayApi()->Cards->Get($CardId);
		} catch(MangoPay\Libraries\ResponseException $e) {
			// handle/log the response exception with code $e->GetCode(), message $e->GetMessage() and error(s) $e->GetErrorDetails() 
		} catch(MangoPay\Libraries\Exception $e) {
			// handle/log the exception $e->GetMessage() 
		}
	}
	public function creatBankAccount(string $userMangoId, string $type, string $iban ,string $bic,string $ownerName,string $ownerAddress)
	{
		try {
	
			$mangoPayApi = $this->getMangoPayApi();
			$BankAccount = new \MangoPay\BankAccount();
			$BankAccount->Type = $type;
			$BankAccount->Details = new MangoPay\BankAccountDetailsIBAN();
			$BankAccount->Details->IBAN = $iban; //"FR7618829754160173622224154";
			$BankAccount->Details->BIC = $bic;//"CMBRFR2BCME";
			$BankAccount->OwnerName = $ownerName;

			$BankAccount->OwnerAddress = new \MangoPay\Address();
			$BankAccount->OwnerAddress->AddressLine1 = $ownerAddress;
			$BankAccount->OwnerAddress->City = "Paris";
			$BankAccount->OwnerAddress->PostalCode = "75001";
			$BankAccount->OwnerAddress->Country = "FR";
			
			$result = $mangoPayApi->Users->CreateBankAccount($userMangoId, $BankAccount);
		} catch(MangoPay\Libraries\ResponseException $e) {
			return $result = $e->GetErrorDetails();
		} catch(MangoPay\Libraries\Exception $e) {
			return $result = $e->GetMessage();
		}
		return $result;
	}

	public function viewBankAccount(string $userMangoId)
	{
		try {
			$mangoPayApi = $this->getMangoPayApi();
			
			$BankAccount = $mangoPayApi->Users->GetBankAccounts($userMangoId);
			
		} catch(MangoPay\Libraries\ResponseException $e) {
			return $BankAccount = $e->GetErrorDetails();
		} catch(MangoPay\Libraries\Exception $e) {
			return $BankAccount = $e->GetMessage();
		}
		return $BankAccount;
	}

	public function verifyKYCBANK(string $idUser)
	{
		$infoUser = $this->getUser($idUser);
		$cards = $this->getCard($idUser);
		

		if ( $infoUser->KYCLevel == "REGULAR"  && $cards ) {
			return true;
		}else{
			return false;
		}

	}


}