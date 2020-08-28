<?php

namespace App\Entity;

use App\Repository\ReglementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReglementRepository::class)
 */
class Reglement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $montant_ttc;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $statut;

    /**
     * @ORM\ManyToMany(targetEntity=MoyenPaiement::class, mappedBy="reglements")
     */
    private $moyenPaiements;

    /**
     * @ORM\OneToOne(targetEntity=Facture::class, inversedBy="reglement", cascade={"persist", "remove"})
     */
    private $facture;

    public function __construct()
    {
        $this->moyenPaiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMontantTtc(): ?float
    {
        return $this->montant_ttc;
    }

    public function setMontantTtc(float $montant_ttc): self
    {
        $this->montant_ttc = $montant_ttc;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|MoyenPaiement[]
     */
    public function getMoyenPaiements(): Collection
    {
        return $this->moyenPaiements;
    }

    public function addMoyenPaiement(MoyenPaiement $moyenPaiement): self
    {
        if (!$this->moyenPaiements->contains($moyenPaiement)) {
            $this->moyenPaiements[] = $moyenPaiement;
            $moyenPaiement->addReglement($this);
        }

        return $this;
    }

    public function removeMoyenPaiement(MoyenPaiement $moyenPaiement): self
    {
        if ($this->moyenPaiements->contains($moyenPaiement)) {
            $this->moyenPaiements->removeElement($moyenPaiement);
            $moyenPaiement->removeReglement($this);
        }

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }
}
