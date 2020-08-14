<?php

namespace App\Entity;

use App\Repository\AnnonceMultimediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceMultimediaRepository::class)
 */
class AnnonceMultimedia extends Annonces
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $systeme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couleur;

    public function getSysteme(): ?string
    {
        return $this->systeme;
    }

    public function setSysteme(?string $systeme): self
    {
        $this->systeme = $systeme;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }
}
