<?php

namespace App\Controller\Trainer;

use App\Controller\Admin\PlayerCrudController as AdminPlayerCrudController;
use App\Entity\Player;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    public const ROUTE_INDEX = 'trainer_dashboard';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/trainer", name="trainer_dashboard")
     */
    public function index(): Response
    {
        return $this->render('@trainer/dashboard/index.twig');
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
        yield MenuItem::linkToCrud(
            $this->translator->trans('menu.planner'),
            'fas fa-calculator',
            Player::class
        )->setController(AdminPlayerCrudController::class);

        yield MenuItem::section($this->translator->trans('menu.section.database'));
        yield MenuItem::linkToCrud(
            $this->translator->trans('menu.trainer.my_players'),
            'fas fa-user',
            Player::class
        )->setController(PlayerCrudController::class);
    }
}
