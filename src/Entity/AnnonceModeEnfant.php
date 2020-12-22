<?php

namespace App\Entity;

use App\Repository\AnnonceModeEnfantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnonceModeEnfantRepository::class)
 */
class AnnonceModeEnfant extends Annonces
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\ManyToOne(targetEntity=TailleEnfant::class)
     */
    private $taille;

    /**
     * @Assert\LessThan(36, message="Trop grand pour un enfant!")
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $pointure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modele;


    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getTaille(): ?TailleEnfant
    {
        return $this->taille;
    }

    public function setTaille(?TailleEnfant $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPointure(): ?int
    {
        return $this->pointure;
    }

    public function setPointure(?int $pointure): self
    {
        $this->pointure = $pointure;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }
}
