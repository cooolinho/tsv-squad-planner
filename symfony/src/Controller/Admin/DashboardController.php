<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Trainer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public const ROUTE_INDEX = 'admin_dashboard';

    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('@admin/dashboard/index.twig', []);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TSV Squad Planner');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Players', 'fas fa-list', Player::class)
            ->setController(PlayerCrudController::class);
        yield MenuItem::linkToCrud('Teams', 'fas fa-list', Team::class);
        yield MenuItem::linkToCrud('Trainer', 'fas fa-list', Trainer::class);
        yield MenuItem::linkToRoute('Planner', 'fas fa-list', 'squad_planner_index');
    }
}
