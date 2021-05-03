<?php

namespace App\Controller;

use App\Service\MangoPayService;
use App\Service\PaginationService;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeLocationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie, TypeLocationRepository $ReptypeLocation, PaginationService $paginator, SessionInterface $session, MangoPayService $mangoPayService)
    {
        /* if($this->getUser() && $this->getUser()->getActivationToken() != null){
            $this->addFlash('danger', 'Vous devez d\'abord cliquer sur le lien d\'activation de compte dans votre email.');
            return $this->redirectToRoute('security_logout');
        } */

        /*dd($this->getUser()->getKilouwers()->filter(function($element) {
            return $element;
        }));*/ 

        $categories = $repCategorie->findAllWithSousCategorie();
        $types      = $ReptypeLocation->findAllOrd();
        $query      = $repAnnonce->findAllAnnonces();
        $annonces   = $paginator->paginate($query, $request->query->getInt('page', 1), 40);

        if ($this->getUser()) {
            //portefeuille
            $portFeuil = $mangoPayService->getWallet($this->getUser()->getMangoPayId());
            $wallet    = $portFeuil;
            $session->set('wallet', $wallet);
        }

        $basepath = dirname(__FILE__,3);
        $pathVar = $basepath."/var/mangopay";
        
        if (!is_dir($pathVar)) {
        mkdir($pathVar, 0777, false);
        }
        

        //Annonces à la une
        $mostViewedAds = $repAnnonce->findMostViewedAds();

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
            'annonces' => $annonces,
            'mostViewedAds' => $mostViewedAds,
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
