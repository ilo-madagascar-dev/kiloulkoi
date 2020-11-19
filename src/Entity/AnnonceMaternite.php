<?php

namespace App\Entity;

use App\Repository\AnnonceMaterniteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceMaterniteRepository::class)
 */
class AnnonceMaternite extends Annonces
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $pointure;

    /**
     * @ORM\ManyToOne(targetEntity=TailleMaternite::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $taille;


    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

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

    public function getTaille(): ?TailleMaternite
    {
        return $this->taille;
    }

    public function setTaille(?TailleMaternite $taille): self
    {
        $this->taille = $taille;

        return $this;
    }
}
