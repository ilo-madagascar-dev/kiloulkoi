<?php

namespace App\Entity;

use App\Repository\TypeAbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeAbonnementRepository::class)
 */
class TypeAbonnement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Abonnement::class, mappedBy="type")
     */
    private $abonnements;

    /**
     * @ORM\Column(type="smallint")
     */
    private $photoMax;

    /**
     * @ORM\Column(type="smallint")
     */
    private $annonceMax;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Abonnement[]
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setType($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->contains($abonnement)) {
            $this->abonnements->removeElement($abonnement);
            // set the owning side to null (unless already changed)
            if ($abonnement->getType() === $this) {
                $abonnement->setType(null);
            }
        }

        return $this;
    }

    public function getPhotoMax(): ?int
    {
        return $this->photoMax;
    }

    public function setPhotoMax(int $photoMax): self
    {
        $this->photoMax = $photoMax;

        return $this;
    }

    public function getAnnonceMax(): ?int
    {
        return $this->annonceMax;
    }

    public function setAnnonceMax(int $annonceMax): self
    {
        $this->annonceMax = $annonceMax;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
