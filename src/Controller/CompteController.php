<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compte")
 */
class CompteController extends AbstractController
{
    /**
     * @Route("/facture", name="compte_facture")
     */
    public function index(): Response
    {
        return $this->render('compte/facture.html.twig', [
            'months' => $month = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre")
        ]);
    }

    /**
     * @Route("/portefeuille", name="compte_portefeuille")
     */
    public function portefeuille(): Response
    {
        return $this->render('compte/portefeuille.html.twig');
    }

    /**
     * @Route("/abonnement", name="compte_abonnement")
     */
    public function abonnement(): Response
    {
        return $this->render('compte/abonnement.html.twig');
    }
}
