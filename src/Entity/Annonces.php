<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnoncesRepository::class)
 */
class Annonces
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
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="annonces" ,cascade={"persist"})
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id", nullable=false)
     */
    private $categorie;
            
    /**
     * @ORM\OneToOne(targetEntity=Vehicule::class, inversedBy="annonces",cascade={"persist"})
     * @ORM\JoinColumn(name="vehicule_id", referencedColumnName="id", nullable=true)
     */
    private $vehicule;
    
    /**
     * @ORM\OneToOne(targetEntity=Immobilier::class, inversedBy="annonces",cascade={"persist"})
     * @ORM\JoinColumn(name="immobilier_id", referencedColumnName="id", nullable=true)
     */
    private $immobilier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getImmobilier(): ?Immobilier
    {
        return $this->immobilier;
    }

    public function setImmobilier(?Immobilier $immobilier): self
    {
        $this->immobilier = $immobilier;

        return $this;
    }

}
