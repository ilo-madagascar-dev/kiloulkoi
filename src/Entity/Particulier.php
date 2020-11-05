<?php

namespace App\Entity;

use App\Repository\ParticulierRepository;
use Doctrine\ORM\Mapping as ORM;

use MangoPay\MangoPayApi;
use MangoPay\UserNatural;
use MangoPay\Wallet;

/**
 * @ORM\Entity(repositoryClass=ParticulierRepository::class)
 */
class Particulier extends User
{
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    public function getNomComplet(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function setMangoPay()
    {
        $mangoPayApi = new \MangoPay\MangoPayApi();

        $mangoPayApi->Config->ClientId        = 'admin-kiloukoi';
        $mangoPayApi->Config->ClientPassword  = 'MNHcmbW6FE5XMeG1M6KgzHZXfAUdAJdeZjmoNDOAQAoi6spMqF';
        $mangoPayApi->Config->TemporaryFolder = '/Temp/tmp';
        $mangoPayApi->Config->BaseUrl         = 'https://api.sandbox.mangopay.com';

        $mangoUser = new \MangoPay\UserNatural();

        $mangoUser->Email              = $this->getEmail();
        $mangoUser->PersonType         = "NATURAL";
        $mangoUser->FirstName          = $this->getPrenom();
        $mangoUser->LastName           = $this->getNom();
        $mangoUser->Birthday           = 1409735187;
        $mangoUser->Nationality        = "FR";
        $mangoUser->CountryOfResidence = "FR";

        $mangoUser = $mangoPayApi->Users->Create($mangoUser);

        $Wallet = new \MangoPay\Wallet();
        $Wallet->Owners = array($mangoUser->Id);
        $Wallet->Description = "Wallet for " . $this->getNomComplet();
        $Wallet->Currency = "EUR";
        
        $mangoPayApi->Wallets->Create($Wallet);

        $this->setMangoPayId( $mangoUser->Id );
        return $this;
    }

    public function getMangoPay()
    {
        
    }
}
