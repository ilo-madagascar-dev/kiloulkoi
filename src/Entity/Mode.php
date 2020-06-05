<?php

namespace App\Entity;

use App\Repository\ModeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModeRepository::class)
 */
class Mode extends Annonces
{
    /**
     * @ORM\Column(type="integer")
     */
    private $pointure;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class, inversedBy="modes" ,cascade={"persist"})
     * @ORM\JoinColumn(name="taille_id", referencedColumnName="id", nullable=false)
     */
    private $taille;

    public function getPointure(): ?int
    {
        return $this->pointure;
    }

    public function setPointure(int $pointure): self
    {
        $this->pointure = $pointure;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
    {
        $this->taille = $taille;

        return $this;
    }
}
