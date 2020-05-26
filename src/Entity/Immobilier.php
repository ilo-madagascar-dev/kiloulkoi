<?php

namespace App\Entity;

use App\Repository\ImmobilierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImmobilierRepository::class)
 */
class Immobilier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;
    
    /**
     * @ORM\OneToOne(targetEntity=Annonces::class, mappedBy="immobilier")
     */
    private $annonces;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAnnonces(): ?Annonces
    {
        return $this->annonces;
    }

    public function setAnnonces(?Annonces $annonces): self
    {
        $this->annonces = $annonces;

        // set (or unset) the owning side of the relation if necessary
        $newImmobilier = null === $annonces ? null : $this;
        if ($annonces->getImmobilier() !== $newImmobilier) {
            $annonces->setImmobilier($newImmobilier);
        }

        return $this;
    }
}
