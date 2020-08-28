<?php

namespace App\Entity;

use App\Repository\CommissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommissionRepository::class)
 */
class Commission
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
     * @ORM\OneToOne(targetEntity=Reglement::class, cascade={"persist", "remove"})
     */
    private $reglement;

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

    public function getReglement(): ?Reglement
    {
        return $this->reglement;
    }

    public function setReglement(?Reglement $reglement): self
    {
        $this->reglement = $reglement;

        return $this;
    }
}
