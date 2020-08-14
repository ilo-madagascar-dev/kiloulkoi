<?php

namespace App\Entity;

use App\Repository\AnnonceSportLoisirRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceSportLoisirRepository::class)
 */
class AnnonceSportLoisir extends Annonces
{
}
