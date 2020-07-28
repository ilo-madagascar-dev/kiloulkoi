<?php

namespace App\Entity;

use App\Repository\HommeFemmeModeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HommeFemmeModeRepository::class)
 */
class HommeFemmeMode extends Mode
{
}
