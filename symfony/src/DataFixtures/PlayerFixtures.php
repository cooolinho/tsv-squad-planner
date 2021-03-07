<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    private static $demoPlayers = [
        ['John', 'Doe', '2000-01-01', Player::FOOT_LEFT],
        ['Peter', 'Pan', '2000-06-01', Player::FOOT_RIGHT],
    ];

    public function load(ObjectManager $manager)
    {
        $demoTeam = $this->getReference(TeamFixtures::DEMO_TEAM);

        foreach (static::$demoPlayers as $demoPlayer) {
            $player = new Player();
            $player->setFirstname($demoPlayer[0]);
            $player->setLastname($demoPlayer[1]);
            $player->setBirthday(new \DateTime($demoPlayer[2]));
            $player->setFoot($demoPlayer[3]);
            $player->addTeam($demoTeam);

            $manager->persist($player);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TeamFixtures::class,
        ];
    }
}
