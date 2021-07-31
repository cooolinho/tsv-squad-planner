<?php

namespace App\DataFixtures;

use App\Entity\Team;
use App\Helper\YouthClassHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public const TEAM_PREFIX = 'team-';

    public function load(ObjectManager $manager): void
    {
        foreach (array_keys(YouthClassHelper::$youthTeams) as $teamIdentifier) {
            $team = new Team();
            $team->setName($teamIdentifier . ' - Jugend');
            $team->setIdentifier($teamIdentifier);
            $team->setIsYouthTeam(true);
            $this->addReference(strtolower(self::TEAM_PREFIX . $teamIdentifier), $team);

            $manager->persist($team);
        }

        $manager->flush();
    }
}
