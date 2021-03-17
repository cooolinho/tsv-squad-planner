<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Helper\YouthClassHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (array_keys(YouthClassHelper::$youthTeams) as $teamIdentifier) {
            $team = new Team();
            $team->setName($teamIdentifier);
            $team->setIsYouthTeam(true);
            $this->addReference($team->getName(), $team);

            $manager->persist($team);
        }

        $manager->flush();
    }
}
