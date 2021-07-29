<?php

namespace App\Controller\Admin;

use Cooolinho\Bundle\SecurityBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseSecurityController
{
    public const ROUTE_LOGIN = 'admin_login';

    /**
     * @Route("/admin/login", name="admin_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return parent::login($authenticationUtils);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
