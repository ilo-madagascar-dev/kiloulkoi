<?php

namespace App\Controller\Annonce;

use App\Entity\User;
use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
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
    private $repUser;

    public function __construct(AnnoncesRepository $repAnnonce, UserRepository $repUser)
    {
        $this->repAnnonce = $repAnnonce;
        $this->repUser    = $repUser;
	}
	

    /**
     * @Route("{proprietaire}/{pseudo}", name="proprietaire_annonce", methods={"GET"})
     */
	public function index(Request $request, User $proprietaire, string $pseudo, PaginationService $paginator) : Response
	{
        if ($proprietaire == null) 
        {
            return $this->redirectToRoute('accueil');
		}
        
        $isFollower = false;
        if( $this->getUser() )
        {
            $isFollower = $this->repUser->isFollowedBy( $proprietaire, $this->getUser() );
        }

        $query         = $this->repAnnonce->findMesAnnonces($proprietaire->getId());
        $annonces      = $paginator->paginate($query, $request->query->getInt('page', 1));
        $annonce_titre = $proprietaire->getPseudo() . ' annonces';
        $discr         = strpos(get_class($proprietaire), 'Professionnel');

        return $this->render('annonces/proprietaire.html.twig', [
            'annonces' => $annonces,
            'discr'    => $discr,
            'proprietaire'  => $proprietaire,
            'kilouwersCount'=> $this->repUser->countKilouwers( $proprietaire ),
            'annoncesCount' => $this->repUser->countAnnonces( $proprietaire ),
            'followed'      => $isFollower,
            'annonce_titre' => $annonce_titre
        ]);
    }
    
    
    /**
     * @Route("{proprietaire}", name="proprietaire_follow", methods={"GET"})
     */
	public function follow(User $proprietaire) : Response
	{
        if( $this->getUser() )
        {
            $isFollower = $this->repUser->isFollowedBy( $proprietaire, $this->getUser() );

            if( $isFollower > 0 )
            {
                $proprietaire->removeKilouwer( $this->getUser() );
            }
            else
            {
                $proprietaire->addKilouwer( $this->getUser() );
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($proprietaire);
            $em->flush();

            $response = ($isFollower > 0) ? 0 : 1;

            return new Response( $response );
        }
    }
}
