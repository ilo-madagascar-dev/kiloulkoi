<?php

namespace App\Entity;

use App\Repository\AnnonceModeFemmeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceModeFemmeRepository::class)
 */
class AnnonceModeFemme extends Annonces
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class,)
     */
    private $taille;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $pointure;

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

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
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

}
