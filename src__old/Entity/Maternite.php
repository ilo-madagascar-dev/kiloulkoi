<?php

namespace App\Entity;

use App\Repository\MaterniteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

/**
 * @ORM\Entity(repositoryClass=MaterniteRepository::class)
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"Maternite" = "Maternite",
 *     "VetementMaternite" = "VetementMaternite",
 * })
 */
class Maternite extends Annonces
{

}
