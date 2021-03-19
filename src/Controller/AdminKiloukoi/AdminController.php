<?php

namespace App\Controller\AdminKiloukoi;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin_kiloukoi",name="admin_kiloukoi")
     */
    public function index()
    {
        return $this->render('admin_kiloukoi/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
