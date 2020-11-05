<?php

namespace App\Controller\Compte;

use App\Entity\Abonnement;
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
    /**
     * @Route("/", name="abonnement_index", methods={"GET"})
     */
    public function index(AbonnementRepository $abonnementRepository, TypeAbonnementRepository $typeAbRepo): Response
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
            'abonnements' => $abonnementRepository->findLatest( $user->getId() )->getResult(),
            'types' => $types
        ]);
    }

    /**
     * @Route("/new", name="abonnement_new", methods={"GET","POST"})
     */
    public function new(Request $request, TypeAbonnementRepository $typeAbRepo): Response
    {
        $mois = $request->request->getInt('mois', 1);
        $user = $this->getUser();

        if (strpos(get_class($user), 'Professionnel') !== false)
        {
            $typeAbonnement = $typeAbRepo->find(2);
        }
        else
        {
            $typeAbonnement = $typeAbRepo->find(1);
        }

        $abonnement = new Abonnement();

        $abonnement->setDateDebut( new \Datetime() );
        $abonnement->setDateFin( (new \Datetime())->add(new \DateInterval('P'. $mois .'M')) );
        $abonnement->setActif( 0 );
        $abonnement->setType( $typeAbonnement );
        $abonnement->setUser( $user );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return $this->redirectToRoute('abonnement_index');
    }

}
