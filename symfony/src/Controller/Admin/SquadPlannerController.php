<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Form\SquadPlannerType;
use App\Helper\YouthClassHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SquadPlannerController extends AbstractController
{
    /**
     * @Route("/squad-planner", name="squad_planner_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SquadPlannerType::class);
        $players = [];
        $from = null;
        $to = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $youthClass = $form->get(SquadPlannerType::CHILD_YOUTH_CLASS)->getData();
            $modifyYears = $form->get(SquadPlannerType::CHILD_MODIFY_YEARS)->getData();

            $from = YouthClassHelper::getDateByYouthClass($youthClass, $modifyYears);
            $to = YouthClassHelper::getDateByYouthClass($youthClass, $modifyYears, false);

            $playerRepository = $this->getDoctrine()->getRepository(Player::class);
            $players = $playerRepository->findInPeriod($from, $to);
        }

        return $this->render('@trainer/squad-planner/index.html.twig', [
            'form' => $form->createView(),
            'players' => $players,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
