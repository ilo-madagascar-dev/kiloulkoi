<?php

namespace App\Entity;

use App\Repository\AnnonceConsoleGamingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceConsoleGamingRepository::class)
 */
class AnnonceConsoleGaming extends Annonces
{
}
