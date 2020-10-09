<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie)
    {
        $categories = $repCategorie->findParents();
        $annonces   = $repAnnonce->findAllAnnonces(10)->getResult();

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
        ]);
    }
}
