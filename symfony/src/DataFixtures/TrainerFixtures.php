<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Entity\Trainer;
use App\Helper\RandomHelper;
use App\Helper\YouthClassHelper;
use Cooolinho\Bundle\SecurityBundle\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrainerFixtures extends UserFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (YouthClassHelper::$youthTeams as $teamIdentifier => $youthTeam) {
            $trainerName = $teamIdentifier . '-trainer';

            $trainer = new Trainer();
            $trainer->setFirstname(RandomHelper::getFirstname());
            $trainer->setLastname(RandomHelper::getLastname());
            $trainer->setEmail($trainerName . '@example.com');
            $trainer->setPlainPassword('secret');
            $trainer->addRole(Trainer::ROLE_TRAINER);

            /** @var Team $team */
            $team = $this->getReference($teamIdentifier);
            $team->setTrainer($trainer);

            $manager->persist($trainer);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TeamFixtures::class];
    }
}
