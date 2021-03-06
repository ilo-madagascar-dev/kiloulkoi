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
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateReservation;

    /**
     * @ORM\ManyToOne(targetEntity=StatutLocation::class, inversedBy="locations" ,cascade={"persist"})
     * @ORM\JoinColumn(name="statut_location_id", referencedColumnName="id", nullable=false)
     */
    private $statutLocation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="location", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /**
     * @ORM\ManyToMany(targetEntity=Facture::class, mappedBy="locations")
     */
    private $factures;

    /**
     * @ORM\ManyToOne(targetEntity=Annonces::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\ManyToMany(targetEntity=Conversation::class, mappedBy="locations")
     */
    private $conversations;

    /**
     * @ORM\OneToOne(targetEntity=Note::class, mappedBy="location", cascade={"persist", "remove"})
     */
    private $note;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->setDateReservation(new \DateTime());
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

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

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
     * @return Collection|Facture[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->addLocation($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->contains($facture)) {
            $this->factures->removeElement($facture);
            $facture->removeLocation($this);
        }

        return $this;
    }

    public function getAnnonce(): ?Annonces
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonces $annonce): self
    {
        $this->annonce = $annonce;

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
            $conversation->addLocation($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeLocation($this);
        }

        return $this;
    }

    public function getNote(): ?Note
    {
        return $this->note;
    }

    public function setNote(Note $note): self
    {
        $this->note = $note;

        // set the owning side of the relation if necessary
        if ($note->getLocation() !== $this) {
            $note->setLocation($this);
        }

        return $this;
    }

    public function __toString()
    {
        return "{$this->id}";
    }
}
