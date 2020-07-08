<?php

namespace App\Entity;

use App\Repository\VetementMaterniteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VetementMaterniteRepository::class)
 */
class VetementMaternite extends Maternite
{

    /**
     * @ORM\ManyToOne(targetEntity=Pointure::class, inversedBy="modes" ,cascade={"persist"})
     * @ORM\JoinColumn(name="pointure_id", referencedColumnName="id", nullable=false)
     */
    private $pointure;

    /**
     * @ORM\ManyToOne(targetEntity=Taille::class, inversedBy="modes" ,cascade={"persist"})
     * @ORM\JoinColumn(name="taille_id", referencedColumnName="id", nullable=false)
     */
    private $taille;

    public function getPointure(): ?Pointure
    {
        return $this->pointure;
    }

    public function setPointure(?Pointure $pointure): self
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
