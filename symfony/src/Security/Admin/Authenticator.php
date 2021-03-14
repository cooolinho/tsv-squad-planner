<?php

namespace App\Security\Admin;

use App\Controller\Admin\DashboardController as AdminDashboardController;
use App\Controller\Admin\SecurityController as AdminSecurityController;
use App\Security\AbstractAuthenticator;

class Authenticator extends AbstractAuthenticator
{
    protected function getLoginRoute(): string
    {
        return AdminSecurityController::ROUTE_LOGIN;
    }

    protected function getAfterLoginRoute(): string
    {
        return AdminDashboardController::ROUTE_INDEX;
    }
}
