<?php

namespace App\Entity;

use App\Repository\ProfessionnelRepository;
use Doctrine\ORM\Mapping as ORM;

use MangoPay;

/**
 * @ORM\Entity(repositoryClass=ProfessionnelRepository::class)
 */
class Professionnel extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raisonSocial;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $siret;

    public function getNomComplet(): string
    {
        return $this->raisonSocial;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(string $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function setMangoPay()
    {
        $mangoPayApi = new \MangoPay\MangoPayApi();

        $mangoPayApi->Config->ClientId        = 'admin-kiloukoi';
        $mangoPayApi->Config->ClientPassword  = 'MNHcmbW6FE5XMeG1M6KgzHZXfAUdAJdeZjmoNDOAQAoi6spMqF';
        $mangoPayApi->Config->TemporaryFolder = '/media/mendrika/Data/dev/Ilo/kiloukoi/var/mangopay';
        $mangoPayApi->Config->BaseUrl         = 'https://api.sandbox.mangopay.com';

        $mangoUser = new \MangoPay\UserNatural();

        $mangoUser->Email              = $this->getEmail();
        $mangoUser->PersonType         = "NATURAL";
        $mangoUser->FirstName          = $this->getRaisonSocial();
        $mangoUser->LastName           = ' - ';
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
}
