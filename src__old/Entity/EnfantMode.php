<?php

namespace App\Entity;

use App\Repository\EnfantModeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnfantModeRepository::class)
 */
class EnfantMode extends Mode
{
}
