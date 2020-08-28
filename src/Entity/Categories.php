<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $className;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="categorieEnfant" ,cascade={"persist"})
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     */
    private $categorieParent;

    /**
     * @ORM\OneToMany(targetEntity=Categories::class, mappedBy="categorieParent")
     */
    private $categorieEnfant;

    /**
     * @ORM\OneToMany(targetEntity=Annonces::class, mappedBy="categorie")
     */
    private $annonces;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $icon;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->categorieEnfant = new ArrayCollection();
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
     * @return Collection|Annonces[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setCategorie($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getCategorie() === $this) {
                $annonce->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCategorieParent(): ?self
    {
        return $this->categorieParent;
    }

    public function setCategorieParent(?self $categorieParent): self
    {
        $this->categorieParent = $categorieParent;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategorieEnfant(): Collection
    {
        return $this->categorieEnfant;
    }

    public function addCategorieEnfant(Categories $categorieEnfant): self
    {
        if (!$this->categorieEnfant->contains($categorieEnfant)) {
            $this->categorieEnfant[] = $categorieEnfant;
            $categorieEnfant->setCategorieParent($this);
        }

        return $this;
    }

    public function removeCategorieEnfant(Categories $categorieEnfant): self
    {
        if ($this->categorieEnfant->contains($categorieEnfant)) {
            $this->categorieEnfant->removeElement($categorieEnfant);
            // set the owning side to null (unless already changed)
            if ($categorieEnfant->getCategorieParent() === $this) {
                $categorieEnfant->setCategorieParent(null);
            }
        }

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function setClassName(string $className): self
    {
        $this->className = $className;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}
