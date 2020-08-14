<?php

namespace App\Controller;

use App\Entity\Annonces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categories;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        $repoCategorie = $this->getDoctrine()->getRepository(Categories::class);
        $repoAnnonce   = $this->getDoctrine()->getRepository(Annonces::class);
        
        $categories = $repoCategorie->findAllWithSousCategorie();
        $annonces   = $repoAnnonce->findAllAnnonces(10)->getResult();
        
        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
        ]);
    }
}
