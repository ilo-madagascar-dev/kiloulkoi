<?php

namespace App\Controller;

use App\Entity\Annonces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categories;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        //find all categories
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $categories = $repository->findAll();
        //find annonces with id other than mine
        $userconnect=$this->getUser()->getId();

        $repositoryAnnonces = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $repositoryAnnonces->findOtherAnnonceById($userconnect);
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'categories' => $categories,
            'annonces' => $annonces
        ]);
    }
}
