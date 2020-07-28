<?php

namespace App\Entity;

use App\Repository\ImmobilierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImmobilierRepository::class)
 */
class Immobilier extends Annonces
{
    /**
     * @ORM\Column(type="integer")
     */
    private $surface;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrChambre;


    public function getSurface()
    {
        return $this->surface;
    }

    public function setSurface($surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getNbrChambre()
    {
        return $this->nbrChambre;
    }

    public function setNbrChambre($nbrChambre): self
    {
        $this->nbrChambre = $nbrChambre;

        return $this;
    }
}
