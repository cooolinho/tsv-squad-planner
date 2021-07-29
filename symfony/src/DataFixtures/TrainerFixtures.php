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
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach (YouthClassHelper::$youthTeams as $teamIdentifier => $youthTeam) {
            $trainerName = $teamIdentifier . '-trainer';

            $trainer = new Trainer();
            $trainer->setFirstname(RandomHelper::getFirstname());
            $trainer->setLastname(RandomHelper::getLastname());
            $trainer->setStreet('Demo-Street');
            $trainer->setStreetNr(RandomHelper::getInt());
            $trainer->setZip('12345');
            $trainer->setCity('Demo City');
            $trainer->setPhone('0123456789');
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
