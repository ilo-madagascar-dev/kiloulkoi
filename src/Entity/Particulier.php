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

    public function setMangoPay(string $appDirectory)
    {
        return $this->setUserMangoPay( $appDirectory, $this->getNom(), $this->getPrenom(), $this->getNomComplet() );
    }

}
