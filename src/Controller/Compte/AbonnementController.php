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
        $user       = $this->getUser();
        $abonnement = $this->repAbonnement->findUserAbonnement( $user->getId() );
        $userType   = strpos(get_class($user), 'Professionnel') !== false ? 'Professionnel' : 'Particulier';

        if( $abonnement == null )
        {
            $abonnement     = new Abonnement();
            $debut          = new \Datetime();
            
            if ($userType == 'Professionnel')
            {
                $typeAbonnement = $this->repTypeAbonnement->find(2); // Professionnel
            }
            else
            {
                $typeAbonnement = $this->repTypeAbonnement->find(1); // Gratuit
            }

            $abonnement->setDateDebut( $debut );
            $abonnement->setDateFin( $debut->add(new \DateInterval('P1M')) );
            $abonnement->setActif( 1 );
            $abonnement->setType( $typeAbonnement );
            $abonnement->setUser( $user );
        }

        return $this->render('compte/abonnement.html.twig', [
            'abonnement' => $abonnement,
            'userType' => $userType
        ]);
    }

    /**
     * @Route("/new", name="abonnement_new", methods={"GET","POST"})
     */
    public function sAbonner(Request $request, TypeAbonnementRepository $typeAbRepo): Response
    {
        $user           = $this->getUser();
        $typeAbonnement = $typeAbRepo->find(3); // Premium
        $abonnement     = new Abonnement();

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


    /**
     * @Route("/stop", name="abonnement_stop", methods={"GET","POST"})
     */
    public function seDesabonner(TypeAbonnementRepository $typeAbRepo): Response
    {
        $user       = $this->getUser();
        $abonnement = $this->repAbonnement->findUserAbonnement( $user->getId() );
        $em         = $this->getDoctrine()->getManager();
        
        $abonnement->setActif(0);
        
        $em->persist($abonnement);
        $em->flush();

        return $this->redirectToRoute('abonnement_index');
    }
}
