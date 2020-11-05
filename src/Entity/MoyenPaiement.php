<?php

namespace App\Entity;

use App\Repository\MoyenPaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoyenPaiementRepository::class)
 */
class MoyenPaiement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Reglement::class, inversedBy="moyenPaiements")
     */
    private $reglements;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $metadata;

    /**
     * @ORM\Column(type="boolean")
     */
    private $favoris;

    /**
     * @ORM\OneToOne(targetEntity=TypeMoyenPaiement::class, cascade={"persist", "remove"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cardType;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ownerId;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cardNumber;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cardexpDate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cardCvx;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creatDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cardId;

    public function __construct()
    {
        $this->reglements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Reglement[]
     */
    public function getReglements(): Collection
    {
        return $this->reglements;
    }

    public function addReglement(Reglement $reglement): self
    {
        if (!$this->reglements->contains($reglement)) {
            $this->reglements[] = $reglement;
        }

        return $this;
    }

    public function removeReglement(Reglement $reglement): self
    {
        if ($this->reglements->contains($reglement)) {
            $this->reglements->removeElement($reglement);
        }

        return $this;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getFavoris(): ?bool
    {
        return $this->favoris;
    }

    public function setFavoris(bool $favoris): self
    {
        $this->favoris = $favoris;

        return $this;
    }

    public function getType(): ?TypeMoyenPaiement
    {
        return $this->type;
    }

    public function setType(?TypeMoyenPaiement $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(?string $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }

    public function setOwnerId(?int $ownerId): self
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardexpDate(): ?string
    {
        return $this->cardexpDate;
    }

    public function setCardexpDate(?string $cardexpDate): self
    {
        $this->cardexpDate = $cardexpDate;

        return $this;
    }

    public function getCardCvx(): ?string
    {
        return $this->cardCvx;
    }

    public function setCardCvx(?string $cardCvx): self
    {
        $this->cardCvx = $cardCvx;

        return $this;
    }

    public function getCreatDate(): ?\DateTimeInterface
    {
        return $this->creatDate;
    }

    public function setCreatDate(?\DateTimeInterface $creatDate): self
    {
        $this->creatDate = $creatDate;

        return $this;
    }

    public function getCardId(): ?int
    {
        return $this->cardId;
    }

    public function setCardId(?int $cardId): self
    {
        $this->cardId = $cardId;

        return $this;
    }
}
