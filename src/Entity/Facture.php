<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
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
    private $date_emission;

    /**
     * @ORM\Column(type="float")
     */
    private $pourcentage_tva;

    /**
     * @ORM\Column(type="float")
     */
    private $montant_ht;

    /**
     * @ORM\Column(type="float")
     */
    private $montant_ttc;

    /**
     * @ORM\Column(type="boolean")
     */
    private $acquittee;

    /**
     * @ORM\OneToOne(targetEntity=Reglement::class, mappedBy="facture", cascade={"persist", "remove"})
     */
    private $reglement;

    /**
     * @ORM\ManyToMany(targetEntity=Abonnement::class, mappedBy="factures")
     */
    private $abonnements;

    /**
     * @ORM\ManyToMany(targetEntity=Location::class, inversedBy="factures")
     */
    private $locations;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
        $this->locations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmission(): ?\DateTimeInterface
    {
        return $this->date_emission;
    }

    public function setDateEmission(\DateTimeInterface $date_emission): self
    {
        $this->date_emission = $date_emission;

        return $this;
    }

    public function getPourcentageTva(): ?float
    {
        return $this->pourcentage_tva;
    }

    public function setPourcentageTva(float $pourcentage_tva): self
    {
        $this->pourcentage_tva = $pourcentage_tva;

        return $this;
    }

    public function getMontantHt(): ?float
    {
        return $this->montant_ht;
    }

    public function setMontantHt(float $montant_ht): self
    {
        $this->montant_ht = $montant_ht;

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

    public function getAcquittee(): ?bool
    {
        return $this->acquittee;
    }

    public function setAcquittee(bool $acquittee): self
    {
        $this->acquittee = $acquittee;

        return $this;
    }

    public function getReglement(): ?Reglement
    {
        return $this->reglement;
    }

    public function setReglement(?Reglement $reglement): self
    {
        $this->reglement = $reglement;

        // set (or unset) the owning side of the relation if necessary
        $newFacture = null === $reglement ? null : $this;
        if ($reglement->getFacture() !== $newFacture) {
            $reglement->setFacture($newFacture);
        }

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
            $abonnement->addFacture($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->contains($abonnement)) {
            $this->abonnements->removeElement($abonnement);
            $abonnement->removeFacture($this);
        }

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
        }

        return $this;
    }
}
