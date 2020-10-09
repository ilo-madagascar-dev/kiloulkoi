<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationRepository::class)
 */
class Conversation
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
    private $derniere_lecture;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_2;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="conversation", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity=Annonces::class, inversedBy="conversations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $lu_1;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $lu_2;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->derniere_lecture = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDerniereLecture(): ?\DateTimeInterface
    {
        return $this->derniere_lecture;
    }

    public function setDerniereLecture(\DateTimeInterface $derniere_lecture): self
    {
        $this->derniere_lecture = $derniere_lecture;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
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

    public function getUser1(): ?User
    {
        return $this->user_1;
    }

    public function setUser1(?User $user_1): self
    {
        $this->user_1 = $user_1;

        return $this;
    }


    public function getUser2(): ?User
    {
        return $this->user_2;
    }

    public function setUser2(?User $user_2): self
    {
        $this->user_2 = $user_2;

        return $this;
    }

    public function getLu1(): ?bool
    {
        return $this->lu_1;
    }

    public function setLu1(bool $lu_1): self
    {
        $this->lu_1 = $lu_1;

        return $this;
    }

    public function getLu2(): ?bool
    {
        return $this->lu_2;
    }

    public function setLu2(bool $lu_2): self
    {
        $this->lu_2 = $lu_2;

        return $this;
    }
}
