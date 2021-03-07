<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public const DEMO_TEAM = 'demo-team';

    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('Demo Team');
        $this->addReference(self::DEMO_TEAM, $team);

        $manager->persist($team);
        $manager->flush();
    }
}
