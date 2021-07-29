<?php

namespace App\Security\Trainer;

use App\Controller\Trainer\DashboardController as TrainerDashboardController;
use App\Controller\Trainer\SecurityController as TrainerSecurityController;
use Cooolinho\Bundle\SecurityBundle\Security\SecurityAuthenticator;

class Authenticator extends SecurityAuthenticator
{
    protected function getLoginRoute(): string
    {
        return TrainerSecurityController::ROUTE_LOGIN;
    }

    protected function getAfterLoginRoute(): string
    {
        return TrainerDashboardController::ROUTE_INDEX;
    }
}
