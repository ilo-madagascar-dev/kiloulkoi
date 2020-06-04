<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Energie;
use App\Entity\Taille;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        // create 20 products! Bam!
        $energies = ['Essence', 'Diesel', 'Electrique'];
        for ($i = 0; $i < count($energies); $i++) {
            $energie = new Energie();
            $energie->setLibelle($energies[$i]);
            $manager->persist($energie);
        }

        $tailles = ['S', 'M', 'L', 'XL'];
        for ($i = 0; $i < count($tailles); $i++) {
            $taille = new Taille();
            $taille->setLibelle($tailles[$i]);
            $manager->persist($taille);
        }

        $categories = ['Vehicule', 'Mode', 'Immobilier'];
        for ($i = 0; $i < count($categories); $i++) {
            $categorie = new Categories();
            $categorie->setLibelle($categories[$i]);
            $manager->persist($categorie);
        }


        $manager->flush();
    }
}
