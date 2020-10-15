<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @ORM\Entity(repositoryClass=AnnoncesRepository::class)
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *     "Annonces" = "Annonces",
 *     "AnnonceVehicule" = "AnnonceVehicule",
 *     "AnnonceImmobilier" = "AnnonceImmobilier",
 *     "AnnonceBricoJardin" = "AnnonceBricoJardin",
 *     "AnnonceConsoleGaming" = "AnnonceConsoleGaming",
 *     "AnnonceDivers" = "AnnonceDivers",
 *     "AnnonceElectromenager" = "AnnonceElectromenager",
 *     "AnnonceImageEtSon" = "AnnonceImageEtSon",
 *     "AnnonceMaternite" = "AnnonceMaternite",
 *     "AnnonceMeubleDeco" = "AnnonceMeubleDeco",
 *     "AnnonceModeFemme" = "AnnonceModeFemme",
 *     "AnnonceModeEnfant" = "AnnonceModeEnfant",
 *     "AnnonceModeHomme" = "AnnonceModeHomme",
 *     "AnnonceMultimedia" = "AnnonceMultimedia",
 *     "AnnonceService" = "AnnonceService",
 *     "AnnonceSportLoisir" = "AnnonceSportLoisir",
 * })
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
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="float")
     */
    private $proucentageTva;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validationAdmin;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="annonces" ,cascade={"persist"})
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id", nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class,cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="annonces", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * @ORM\Column(type="integer")
     */
    private $visite;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="annonce", orphanRemoval=true)
     */
    private $conversations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class)
     */
    private $sousCategorie;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="annonce")
     */
    private $locations;

    /**
     * @ORM\Column(type="smallint")
     */
    private $unite;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
        $this->dateCreation = new \Datetime();
        $this->conversations = new ArrayCollection();
        $this->locations = new ArrayCollection();
    }

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto(): void
    {
        $this->photo = new ArrayCollection();
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setAnnonces($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->contains($photo)) {
            $this->photo->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getAnnonces() === $this) {
                $photo->setAnnonces(null);
            }
        }

        return $this;
    }

    public function getProucentageTva(): ?float
    {
        return $this->proucentageTva;
    }

    public function setProucentageTva(float $proucentageTva): self
    {
        $this->proucentageTva = $proucentageTva;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(): self
    {
        $this->dateModification = new \Datetime();
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getValidationAdmin(): ?bool
    {
        return $this->validationAdmin;
    }

    public function setValidationAdmin(bool $validationAdmin): self
    {
        $this->validationAdmin = $validationAdmin;

        return $this;
    }

    // public function getType(): ?string
    // {
    //     return $this->type;
    // }

    // public function setType(string $type): self
    // {
    //     $this->type = $type;

    //     return $this;
    // }

    public function getVisite(): ?int
    {
        return $this->visite;
    }

    public function setVisite(int $visite): self
    {
        $this->visite = $visite;

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setAnnonce($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getAnnonce() === $this) {
                $conversation->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(): self
    {
        $slugger    = new AsciiSlugger();
        $this->slug = $slugger->slug($this->titre);

        return $this;
    }

    public function getSousCategorie(): ?Categories
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(?Categories $sousCategorie): self
    {
        $this->sousCategorie = $sousCategorie;

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
            $location->setAnnonce($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getAnnonce() === $this) {
                $location->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getUnite(): ?int
    {
        return $this->unite;
    }

    public function getUniteLibelle(): ?string
    {
        $libelle = [
            'par mois',
            'par jours',
            'par heure',
        ];

        return $libelle[$this->unite] ? $libelle[$this->unite] : $libelle[0];
    }

    public function setUnite(int $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

}
