<?php

namespace App\Entity;

use App\Repository\ModeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

/**
 * @ORM\Entity(repositoryClass=ModeRepository::class)
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"Mode" = "Mode",
 *     "HommeFemmeMode" = "HommeFemmeMode",
 *     "EnfantMode" = "EnfantMode"
 * })
 */
class Mode extends Annonces
{
    /**
     * @ORM\ManyToOne(targetEntity=Pointure::class ,cascade={"persist"})
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
