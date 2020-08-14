<?php

namespace App\Entity;

use App\Repository\AnnonceVehiculeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceVehiculeRepository::class)
 */
class AnnonceVehicule extends Annonces
{

    /**
     * @ORM\ManyToOne(targetEntity=Propriete::class, inversedBy="marque")
     */
    private $energie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele;

    public function getEnergie(): ?Propriete
    {
        return $this->energie;
    }

    public function setEnergie(?Propriete $energie): self
    {
        $this->energie = $energie;

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

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }
}
