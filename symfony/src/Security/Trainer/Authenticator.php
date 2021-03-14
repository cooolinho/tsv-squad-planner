<?php

namespace App\Security\Trainer;

use App\Controller\Trainer\DashboardController as TrainerDashboardController;
use App\Controller\Trainer\SecurityController as TrainerSecurityController;
use App\Security\AbstractAuthenticator;

class Authenticator extends AbstractAuthenticator
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
