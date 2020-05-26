<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function index()
    {
        return $this->render('calendrier/index.html.twig', [
            'controller_name' => 'CalendrierController',
        ]);
    }
}
