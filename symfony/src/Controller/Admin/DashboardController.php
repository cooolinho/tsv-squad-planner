<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\Trainer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    public const ROUTE_INDEX = 'admin_dashboard';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
            ->setTitle($this->translator->trans('menu.dashboard.title'));
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard($this->translator->trans('menu.dashboard.label'), 'fa fa-home');

        yield MenuItem::section($this->translator->trans('menu.section.planner'));
        yield MenuItem::linkToRoute(
            $this->translator->trans('menu.planner'),
            'fas fa-calculator', 'squad_planner_index'
        );

        yield MenuItem::section($this->translator->trans('menu.section.database'));
        yield MenuItem::linkToCrud(
            $this->translator->trans('menu.entity.player'),
            'fas fa-user',
            Player::class
        )->setController(PlayerCrudController::class);

        yield MenuItem::linkToCrud(
            $this->translator->trans('menu.entity.team'),
            'fas fa-users',
            Team::class
        );
        yield MenuItem::linkToCrud(
            $this->translator->trans('menu.entity.club'),
            'fas fa-church', Club::class
        );

        yield MenuItem::section($this->translator->trans('menu.section.configuration'));
        yield MenuItem::linkToCrud(
            $this->translator->trans('menu.entity.trainer'),
            'fas fa-user-secret',
            Trainer::class
        );

        yield MenuItem::section($this->translator->trans('menu.section.account'));
        yield MenuItem::linkToLogout($this->translator->trans('menu.logout'), 'fas fa-sign-out-alt');
    }
}
