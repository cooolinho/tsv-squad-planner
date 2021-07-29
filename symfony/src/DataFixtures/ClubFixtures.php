<?php

namespace App\DataFixtures;

use App\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClubFixtures extends Fixture
{
    public const DEMO_CLUBS = ['TSV Großenkneten', 'FC Huntlosen'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DEMO_CLUBS as $clubName) {
            $club = new Club();
            $club->setName($clubName);

            $this->addReference($clubName, $club);

            $manager->persist($club);
        }

        $manager->flush();
    }
}
