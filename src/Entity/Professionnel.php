<?php

namespace App\Entity;

use App\Repository\ProfessionnelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use MangoPay;

/**
 * @ORM\Entity(repositoryClass=ProfessionnelRepository::class)
 * @UniqueEntity(
 *     fields={"siret"},
 *     message="Siret déjà enregistré."
 * )
 */
class Professionnel extends User
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raisonSocial;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
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
}
