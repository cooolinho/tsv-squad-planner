<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Trainer;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TrainerListener
{
    private UserPasswordHasherInterface $passwordEncoder;
    private LoggerInterface $logger;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, LoggerInterface $logger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
    }

    public function prePersist(Trainer $user, LifecycleEventArgs $event): void
    {
        $this->hashUserPassword($user);
    }

    private function hashUserPassword(Trainer $user): void
    {
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $this->logger->log(LogLevel::INFO, $user->getEmail() . ' password encrypted!');
    }
}
