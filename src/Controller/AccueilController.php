<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeLocationRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\MangoPayService;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie, TypeLocationRepository $ReptypeLocation, PaginationService $paginator,SessionInterface $session,MangoPayService $mangoPayService)
    {
        $categories = $repCategorie->findAllWithSousCategorie();
        $types      = $ReptypeLocation->findAllOrd();
        $query      = $repAnnonce->findAllAnnonces();
        $annonces   = $paginator->paginate($query, $request->query->getInt('page', 1));

        if ($this->getUser()) {
            //portefeuille 
            $portFeuil = $waletUserId = $mangoPayService->getWallet($this->getUser()->getMangoPayId());

            $wallet = $session->get('wallet');
       
            $wallet = $portFeuil;
       
            $session->set('wallet', $wallet);
        }
        

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'types' => $types,
        ]);
    }
}
