<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
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
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;


    /**
     * @ORM\ManyToOne(targetEntity=StatutLocation::class, inversedBy="locations" ,cascade={"persist"})
     * @ORM\JoinColumn(name="statut_location_id", referencedColumnName="id", nullable=false)
     */
    private $statutLocation;

    /**
     * @ORM\ManyToOne(targetEntity=Annonces::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="annonces_id", referencedColumnName="id", nullable=false)
     */
    private $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    //id client

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getStatutLocation(): ?StatutLocation
    {
        return $this->statutLocation;
    }

    public function setStatutLocation(?StatutLocation $statutLocation): self
    {
        $this->statutLocation = $statutLocation;

        return $this;
    }

    /**
     * @return Collection|Annonces[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function setAnnonces(?Annonces $annonces): self
    {
        $this->annonces = $annonces;

        return $this;
    }


}
