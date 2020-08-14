<?php

namespace App\Entity;

use App\Repository\AnnonceImmobilierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceImmobilierRepository::class)
 */
class AnnonceImmobilier extends Annonces
{
    /**
     * @ORM\Column(type="float")
     */
    private $surface;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $chambre;


    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(?int $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getChambre(): ?int
    {
        return $this->chambre;
    }

    public function setChambre(?int $chambre): self
    {
        $this->chambre = $chambre;

        return $this;
    }
}
