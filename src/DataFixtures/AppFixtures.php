<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Energie;
use App\Entity\Pointure;
use App\Entity\StatutLocation;
use App\Entity\Taille;
use App\Entity\TypeLocation;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures implements FixtureInterface
{
    function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // create 20 products! Bam!
        //pll
        $energies = ['Essence', 'Diesel', 'Electrique'];
        for ($i = 0; $i < count($energies); $i++) {
            $energie = new Energie();
            $energie->setLibelle($energies[$i]);
            $manager->persist($energie);
        }

        //taille HommeFemmeMode
        //XS
        $tailles = ['S', 'M', 'L', 'XL', 'XS', 'XXl', 'XXXL'];
        for ($i = 0; $i < count($tailles); $i++) {
            $taille = new Taille();
            $taille->setLibelle($tailles[$i]);
            $taille->setClasse("HommeFemmeMode");
            $manager->persist($taille);
        }

        //pointure HommeFemmeMode
        //femme 36
        for ($i = 37; $i <= 50; $i++) {
            $pointure = new Pointure();
            $pointure->setLibelle($i);
            $pointure->setClasse("HommeFemmeMode");
            $manager->persist($pointure);
        }

        //taille VetementMaternite
        $tailles = ["Prématuré", "Naissance", "0-3mois", "3-6mois", "6-9mois", "9-12mois", "12-18mois", "18-24mois", "24-36mois"];
        for ($i = 0; $i < count($tailles); $i++) {
            $taille = new Taille();
            $taille->setLibelle($tailles[$i]);
            $taille->setClasse("VetementMaternite");
            $manager->persist($taille);
        }

        //pointure VetementMaternite
        $pointures = ["Prématuré", "Naissance", "0-3mois", "3-6mois", "6-9mois", "9-12mois", "12-18mois", "18-24mois", "24-36mois"];
        for ($i = 0; $i < count($pointures); $i++) {
            $pointure = new Pointure();
            $pointure->setLibelle($pointures[$i]);
            $pointure->setClasse("VetementMaternite");
            $manager->persist($pointure);
        }

        //taille EnfantMode
        for ($i = 3; $i <= 16; $i++) {
            $taille = new Taille();
            $taille->setLibelle($i);
            $taille->setClasse("EnfantMode");
            $manager->persist($taille);
        }

        //pointure EnfantMode
        for ($i = 12; $i <= 37; $i++) {
            $pointure = new Pointure();
            $pointure->setLibelle($i);
            $pointure->setClasse("EnfantMode");
            $manager->persist($pointure);
        }

        $categories = ['Vehicule', 'Mode', 'Immobilier'];
        for ($i = 0; $i < count($categories); $i++) {
            $categorie = new Categories();
            $categorie->setLibelle($categories[$i]);
            $manager->persist($categorie);
        }

        $statuLocations = ['En attente', 'En cours', 'Effectué', 'Interrompu'];
        for ($i = 0; $i < count($statuLocations); $i++) {
            $statuLocation = new StatutLocation();
            $statuLocation->setLibelle($statuLocations[$i]);
            $manager->persist($statuLocation);
        }

        //type location
        $typeLocations = ["heure", "jour", "semaine", "mois"];
        for ($i = 0; $i < count($typeLocations); $i++) {
            $typeLocation = new TypeLocation();
            $typeLocation->setLibelle($typeLocations[$i]);
            $manager->persist($typeLocation);
        }

        $manager->flush();
    }
}
