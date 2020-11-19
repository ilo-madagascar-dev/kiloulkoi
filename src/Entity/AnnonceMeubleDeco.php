<?php

namespace App\Entity;

use App\Repository\AnnonceMeubleDecoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceMeubleDecoRepository::class)
 */
class AnnonceMeubleDeco extends Annonces
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;


    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

}
