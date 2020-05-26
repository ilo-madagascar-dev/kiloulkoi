<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class mdpController extends AbstractController
{
    /**
     * @Route("/mdp", name="mdp")
     */
    public function index()
    {
        return $this->render('mdp/index.html.twig', [
            'controller_name' => 'mdpController',
        ]);
    }
}
