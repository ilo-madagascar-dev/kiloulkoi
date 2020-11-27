<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeLocationRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie, TypeLocationRepository $reptypeLocation, PaginationService $paginator)
    {
        $categories = $repCategorie->findAllWithSousCategorie();
        $types      = $reptypeLocation->findAllOrd();
        $query      = $repAnnonce->findAllAnnonces();
        $annonces   = $paginator->paginate($query, $request->query->getInt('page', 1));

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'types' => $types,
        ]);
    }


    /**
     * @Route("/api/init", name="api_init")
     */
    public function categories(CategoriesRepository $repCategorie, SerializerInterface $serializer)
    {
        $categories = $repCategorie->findAllWithSousCategorie();
        // dd( $categories );
        $categories = $serializer->normalize($categories, null, [
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'annonces', 
                'className',
                'id'
            ]
        ]);
        
        $datas = json_encode([
            'categories' => $categories,
        ]);
        return new Response($datas, 200, ['Content-Type' => 'application/json']);
    }
}
