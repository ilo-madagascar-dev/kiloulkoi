<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"})
 * 
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"User" = "User", "Particulier" = "Particulier", "Professionnel" = "Professionnel"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="user")
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="user", orphanRemoval=true)
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity=Annonces::class, mappedBy="user", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateMiseAJour;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $telephone;

    /**
     * @ORM\Column(type="smallint")
     */
    private $genre;

    /**
     * @ORM\ManyToMany(targetEntity=Annonces::class, inversedBy="user_favoris")
     */
    private $favoris;

    /**
     * @ORM\OneToMany(targetEntity=Abonnement::class, mappedBy="user")
     */
    private $abonnement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mangoPayId;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="kilouwees")
     */
    private $kilouwers;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="kilouwers")
     */
    private $kilouwees;
    
    public function __construct()
    {
        $this->location = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->abonnement = new ArrayCollection();
        $this->kilouwers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->location->contains($location)) {
            $this->location[] = $location;
            $location->setUser($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->location->contains($location)) {
            $this->location->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getUser() === $this) {
                $location->setUser(null);
            }
        }

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
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

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
            $conversation->setUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            // set the owning side to null (unless already changed)
            if ($conversation->getUser() === $this) {
                $conversation->setUser(null);
            }
        }

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
            $annonce->setUser($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getUser() === $this) {
                $annonce->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(): self
    {
        $this->date_creation = new \Datetime();

        return $this;
    }

    public function getDateMiseAJour(): ?\DateTimeInterface
    {
        return $this->dateMiseAJour;
    }

    public function setDateMiseAJour(): self
    {
        $this->dateMiseAJour = new \Datetime();

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getGenre(): ?int
    {
        return $this->genre;
    }

    public function setGenre(int $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection|Annonces[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Annonces $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(Annonces $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
        }

        return $this;
    }

    /**
     * @return Collection|Abonnement[]
     */
    public function getAbonnement(): Collection
    {
        return $this->abonnement;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnement->contains($abonnement)) {
            $this->abonnement[] = $abonnement;
            $abonnement->setUser($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnement->contains($abonnement)) {
            $this->abonnement->removeElement($abonnement);
            // set the owning side to null (unless already changed)
            if ($abonnement->getUser() === $this) {
                $abonnement->setUser(null);
            }
        }

        return $this;
    }

    public function getMangoPayId(): ?string
    {
        return $this->mangoPayId;
    }

    public function setMangoPayId(string $mangoPayId): self
    {
        $this->mangoPayId = $mangoPayId;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getKilouwers(): Collection
    {
        return $this->kilouwers;
    }

    public function addKilouwer(self $kilouwer): self
    {
        if (!$this->kilouwers->contains($kilouwer)) {
            $this->kilouwers[] = $kilouwer;
        }

        return $this;
    }

    public function removeKilouwer(self $kilouwer): self
    {
        $this->kilouwers->removeElement($kilouwer);

        return $this;
    }


}
