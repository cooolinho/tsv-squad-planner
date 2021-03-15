<?php

namespace App\DataFixtures;

use App\Entity\Trainer;
use App\Helper\RandomHelper;
use App\Helper\YouthClassHelper;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrainerFixtures extends UserFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (YouthClassHelper::$youthTeams as $teamIdentifier => $youthTeam) {
            $trainerName = $teamIdentifier . '-' . TeamFixtures::YOUTH . '-trainer';

            $trainer = new Trainer();
            $trainer->setFirstname(RandomHelper::getFirstname());
            $trainer->setLastname(RandomHelper::getLastname());
            $this->addDemoUserData($trainer, $trainerName, Trainer::ROLE_TRAINER);

            $team = $this->getReference(TeamFixtures::getTeamName($teamIdentifier));
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
