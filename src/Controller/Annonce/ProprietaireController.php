<?php

namespace App\Controller\Annonce;

use App\Entity\User;
use App\Repository\AbonnementRepository;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeAbonnementRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonces-proprietaire/")
 */
class ProprietaireController extends AbstractController
{
    private $repAnnonce;
    private $repCategorie;
    private $repAbonnement;
    private $repTypeAbonnement;

    public function __construct(AnnoncesRepository $repAnnonce, CategoriesRepository $repCategorie, AbonnementRepository $repAbonnement, TypeAbonnementRepository $repTypeAbonnement)
    {
        $this->repAnnonce = $repAnnonce;
        $this->repCategorie = $repCategorie;
        $this->repAbonnement = $repAbonnement;
        $this->repTypeAbonnement = $repTypeAbonnement;
	}
	

    /**
     * @Route("{user}/{pseudo}", name="annonce_proprietaire", methods={"GET"})
     */
	public function index(Request $request, User $user, string $pseudo, PaginationService $paginator) : Response
	{
        if ($user == null) 
        {
            return $this->redirectToRoute('accueil');
		}
		
        $query         = $this->repAnnonce->findMesAnnonces($user->getId());
        $annonces      = $paginator->paginate($query, $request->query->getInt('page', 1));
        $annonce_titre = $user->getPseudo() . ' annonces';
        $discr         = strpos(get_class($user), 'Professionnel');

        return $this->render('annonces/proprietaire.html.twig', [
            'annonces' => $annonces,
            'discr'    => $discr,
            'proprietaire'  => $user,
            'annonce_titre' => $annonce_titre
        ]);
	}

}
