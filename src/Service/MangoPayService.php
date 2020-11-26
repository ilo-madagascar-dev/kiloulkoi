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


}