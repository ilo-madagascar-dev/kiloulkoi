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
     * @ORM\ManyToOne(targetEntity=Propriete::class)
     */
    private $pointure;


    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getPointure(): ?Propriete
    {
        return $this->pointure;
    }

    public function setPointure(?Propriete $pointure): self
    {
        $this->pointure = $pointure;

        return $this;
    }
}
