<?php

namespace App\Controller\Compte;

use App\Entity\Abonnement;
use App\Entity\User;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use App\Repository\TypeAbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/abonnement")
 */
class AbonnementController extends AbstractController
{
    private $repAbonnement;
    private $repTypeAbonnement;

    public function __construct(AbonnementRepository $repAbonnement, TypeAbonnementRepository $repTypeAbonnement)
    {
        $this->repAbonnement = $repAbonnement;
        $this->repTypeAbonnement = $repTypeAbonnement;
    }

    /**
     * @Route("/", name="abonnement_index", methods={"GET"})
     */
    public function index(TypeAbonnementRepository $typeAbRepo): Response
    {
        $user  = $this->getUser();

        if (strpos(get_class($user), 'Professionnel') !== false)
        {
            $types = $typeAbRepo->findParticulierType();
        }
        else
        {
            $types = $typeAbRepo->findProType();
        }

        return $this->render('compte/abonnement.html.twig', [
            'abonnement' => $this->getUserAbonnement( $user ),
            'types' => $types
        ]);
    }

    /**
     * @return Abonnement
     */
    private function getUserAbonnement(User $user): Abonnement
    {
        $abonnement     = $this->repAbonnement->findUserAbonnement( $user->getId() ); 
        if( $abonnement == null )
        {
            $abonnement     = new Abonnement();
            $typeAbonnement = $this->repTypeAbonnement->find(1);
            $debut          = new \Datetime();

            $abonnement->setDateDebut( $debut );
            $abonnement->setDateFin( $debut->add(new \DateInterval('P1M')) );
            $abonnement->setActif( 1 );
            $abonnement->setType( $typeAbonnement );
            $abonnement->setUser( $user );
        }

        return $abonnement;
    }

    /**
     * @Route("/new", name="abonnement_new", methods={"GET","POST"})
     */
    public function new(Request $request, TypeAbonnementRepository $typeAbRepo): Response
    {
        $user = $this->getUser();
        if (strpos(get_class($user), 'Professionnel') !== false)
        {
            $typeAbonnement = $typeAbRepo->find(2); // Professionnel
        }
        else
        {
            $typeAbonnement = $typeAbRepo->find(3); // Premium
        }

        $abonnement = new Abonnement();

        $abonnement->setDateDebut( new \Datetime() );
        $abonnement->setDateFin( (new \Datetime())->add(new \DateInterval('P1M')) );
        $abonnement->setActif( 1 );
        $abonnement->setType( $typeAbonnement );
        $abonnement->setUser( $user );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return $this->redirectToRoute('abonnement_index');
    }

}
