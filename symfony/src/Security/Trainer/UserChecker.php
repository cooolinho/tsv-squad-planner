<?php

namespace App\Security\Trainer;

use App\Entity\Trainer;
use App\Exception\FalseLoginException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Trainer) {
            throw new FalseLoginException('This account is not a trainer');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof Trainer) {
            throw new FalseLoginException('This account is not a trainer');
        }
    }
}
