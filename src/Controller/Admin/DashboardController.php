<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\Conversation;
use App\Entity\Location;
use App\Entity\Note;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(AnnoncesCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kiloukoi WEBAPP');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Global');
        yield MenuItem::linkToCrud('Annonces', 'fas fa-bookmark', Annonces::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Categories::class);
        yield MenuItem::linkToCrud('Locations', 'fas fa-universal-access', Location::class);
        yield MenuItem::linkToCrud('Conversations', 'fas fa-comment', Conversation::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Notes', 'fas fa-sticky-note', Note::class);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin/admin.css');
    }
}
