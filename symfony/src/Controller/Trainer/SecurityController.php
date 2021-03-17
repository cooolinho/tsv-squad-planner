<?php

namespace App\Controller\Trainer;

use Cooolinho\Bundle\SecurityBundle\Controller\AbstractSecurityController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractSecurityController
{
    public const ROUTE_LOGIN = 'trainer_login';

    /**
     * @Route("/trainer/login", name="trainer_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return parent::login($authenticationUtils);
    }

    /**
     * @Route("/trainer/logout", name="trainer_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
