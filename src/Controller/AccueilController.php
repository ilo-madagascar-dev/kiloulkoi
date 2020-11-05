<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeLocationRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie, TypeLocationRepository $ReptypeLocation, PaginationService $paginator)
    {
        $categories = $repCategorie->findParents();
        $types      = $ReptypeLocation->findAllOrd();
        $query      = $repAnnonce->findAllAnnonces();
        $annonces   = $paginator->paginate($query, $request->query->getInt('page', 1));

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'types' => $types,
        ]);
    }
}
