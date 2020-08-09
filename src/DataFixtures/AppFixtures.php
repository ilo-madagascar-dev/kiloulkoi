<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Energie;
use App\Entity\Pointure;
use App\Entity\SousCategorie;
use App\Entity\StatutLocation;
use App\Entity\Taille;
use App\Entity\TypeLocation;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures implements FixtureInterface
{
    function load(ObjectManager $manager)
    {
        //list categorie
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

        // $categories = [['Véhicule', 'Vehicule', ''], ['Immobilier', 'Immobilier', ''],
        //     ['Mode', 'Mode', ''], ['Maternité', 'Maternite', ''], ['Service', 'Service', ''],
        //     ['Homme', 'HommeFemmeMode', 'Mode'], ['Femme', 'HommeFemmeMode', 'Mode'],
        //     ['Enfant', 'EnfantMode', 'Mode'], ['Vêtements', 'VetementMaternite', 'Maternite']];

        $categories = [
            "Vehicule" => [
                'icon' => 'fa fa-car',
                'libelle' => "VÉHICULE",
                'sub_cat' => [
                    "Voiture",
                    "Moto",
                    "Scooter",
                    "Vélo",
                    "Trottinette Électrique ",
                    "Camion Fourgon",
                    "Camion Déménagement ",
                    "Camion Plateau",
                    "Camion Frigorifique ",
                    "Remorque ",
                    "Équipement Voiture",
                    "Équipement Moto & Scooter",
                    "Équipement Remorque "
                ]
            ],
            "Immobilier " => [
                'icon' => 'fa fa-home',
                'libelle' => "IMMOBILIER",
                'sub_cat' => [
                    "Maison",
                    "Appartement",
                    "Gîte",
                    "Local Bureau",
                    "Garage & Box",
                    "Parking",
                    "Terrain"
                ]
            ],
            "Multimedia" => [
                'icon' => 'fas fa-photo-video',
                'libelle' => "MULTIMÉDIA",
                'sub_cat' => [
                    "Smartphone & Mobile",
                    "PC Portable (Laptop)",
                    "PC Bureau (Desktop)",
                    "Tout en un",
                    "Tablette Android",
                    "Tablette iOS",
                    "Tablette Windows",
                    "Accessoires PC",
                    "Accessoires Tablette"
                ]
            ],
            "ImageEtSon" => [
                'icon' => 'fas fa-volume-up',
                'libelle' => "IMAGE & SON",
                'sub_cat' => [
                    "Écran Tv",
                    "Écran PC",
                    "Vidéo Projecteur ",
                    "Appareil Photo & Caméscope ",
                    "Home-Cinéma",
                    "Casque",
                    "Enceinte Connectée ",
                    "Matériel Professionnel ",
                    "Vintage"
                ]
            ],
            "ConsoleGaming"  => [
                'icon' => 'fas fa-gamepad',
                'libelle' => "CONSOLE - GAMING",
                'sub_cat' => [
                    "PlayStation",
                    "Xbox",
                    "Nintendo",
                    "PC Gaming",
                    "Console Vintage",
                    "Jeux PlayStation ",
                    "Jeux Xbox ",
                    "Jeux Nintendo",
                    "Accessoires PlayStation - VR",
                    "Accessoires Xbox",
                    "Accessoires Nintendo",
                    "Accessoires PC Gaming",
                    "Accessoires Vintage"
                ]
            ],
            "MeubleDeco" => [
                'icon' => 'fas fa-couch',
                'libelle' => "MEUBLE & DÉCO",
                'sub_cat' => [
                    "Meuble Salon",
                    "Meuble Chambre",
                    "Meuble Cuisine",
                    "Meuble Jardin",
                    "Luminaire Intérieur ",
                    "Luminaire Extérieur ",
                    "Luminaire Professionnel ",
                    "Décoration Murale",
                    "Décoration Table",
                    "Décoration Enfant",
                    "Plante d’intérieur"
                ]
            ],
            "Electromenager" => [
                'icon' => 'fas fa-tv',
                'libelle' => "ÉLECTROMÉNAGER",
                'sub_cat' => [
                    "Cuisine",
                    "Entretien ",
                    "Linge ",
                    "Beauté"
                ]
            ],
            "Maternite" => [
                'icon' => 'fas fa-baby-carriage',
                'libelle' => "MATERNITÉ",
                'sub_cat' => [
                    "Ameublement ",
                    "Balade",
                    "Vêtements ",
                    "Accessoires ",
                    "Jeux"
                ]
            ],
            "HommeFemmeMode" => [
                'icon' => 'fas fa-male',
                'libelle' => "MODE HOMME",
                'sub_cat' => [
                    "Haut",
                    "Bas",
                    "Chaussures ",
                    "Accessoires",
                    "Montre",
                    "Ceinture",
                    "Bijoux",
                    "Lunettes",
                    "Bagagerie - Maroquinerie ",
                    "Déguisement",
                    "Luxe"
                ]
            ],
            "HommeFemmeMode" => [
                'icon' => 'fas fa-female',
                'libelle' => "MODE FEMME",
                'sub_cat' => [
                    "Haut",
                    "Bas",
                    "Chaussures ",
                    "Accessoires",
                    "Montre",
                    "Ceinture",
                    "Bijoux",
                    "Lunettes ",
                    "Bagagerie - Maroquinerie",
                    "Déguisement ",
                    "Luxe"
                ]
            ],
            "EnfantMode" => [
                'icon' => 'fas fa-baby',
                'libelle' => "MODE ENFANT",
                'sub_cat' => [
                    "Haut",
                    "Bas",
                    "Chaussures ",
                    "Accessoires",
                    "Montre",
                    "Ceinture",
                    "Bijoux",
                    "Lunettes",
                    "Bagagerie",
                    "Déguisement ",
                    "Luxe"
                ]
            ],
            "BricoJardin" => [
                'icon' => 'fas fa-tools',
                'libelle' => "BRICO & JARDINS",
                'sub_cat' => [
                    "Machine électrique ",
                    "Machine Pro",
                    "Jardinage ",
                    "Tondeuse",
                    "Serre",
                    "Outillage",
                    "Outils Pro"
                ]
            ],
            "SportLoisir" => [
                'icon' => 'fas fa-volleyball-ball',
                'libelle' => "SPORT & LOISIRS",
                'sub_cat' => [
                    "Aquatique & Plage",
                    "Équestre ",
                    "Sport Collectif ",
                    "Fitness ",
                    "Combat",
                    "Livres",
                    "DVD - Blu Ray",
                    "Musique",
                    "Jeux Société "
                ]
            ],
            "Service" => [
                'icon' => 'fas fa-handshake',
                'libelle' => "SERVICES",
                'sub_cat' => []
            ],
            "Divers" => [
                'icon' => 'fas fa-network-wired',
                'libelle' => "DIVERS",
                'sub_cat' => []
            ],
        ];

        foreach ($categories as $name => $details)
        {
            $categorie = new Categories();

            $categorie->setLibelle($details['libelle']);
            $categorie->setClassName(trim($name));
            $categorie->setIcon($details['icon']);
            
            $manager->persist($categorie);

            foreach ($details['sub_cat'] as $sub_cat)
            {
                $slugger       = new AsciiSlugger();
                $sub_categorie = new SousCategorie();

                $sub_categorie->setLibelle($sub_cat);
                $sub_categorie->setSlug( $slugger->slug($sub_cat) );
                $sub_categorie->setCategorie( $categorie );

                $manager->persist($sub_categorie);
            }
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
