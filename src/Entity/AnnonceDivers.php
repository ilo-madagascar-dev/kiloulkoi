<?php

namespace App\Entity;

use App\Repository\AnnonceDiversRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceDiversRepository::class)
 */
class AnnonceDivers extends Annonces
{
}
