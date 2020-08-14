<?php

namespace App\Entity;

use App\Repository\AnnonceServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceServiceRepository::class)
 */
class AnnonceService extends Annonces
{
}
