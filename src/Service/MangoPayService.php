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
}