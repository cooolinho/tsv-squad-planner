<?php

namespace App\Controller\Trainer;

use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public const ROUTE_INDEX = 'trainer_dashboard';

    /**
     * @Route("/trainer", name="trainer_dashboard")
     */
    public function index(): Response
    {
        return $this->render('@trainer/dashboard/index.twig', []);
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
    }
}
