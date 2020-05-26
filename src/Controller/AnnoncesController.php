<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/annonces",name="annonces")
     */
    public function index()
    {
        return $this->render('annonces/index.html.twig', [
            'controller_name' => 'AnnoncesController',
        ]);
    }
}
