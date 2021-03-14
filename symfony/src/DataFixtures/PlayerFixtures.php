<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Helper\RandomHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    private const PLAYER_COUNT_IN_TEAM = 25;

    public function load(ObjectManager $manager): void
    {
        foreach (TeamFixtures::$youthTeams as $teamIdentifier => $youthTeam) {
            $team = $this->getReference(TeamFixtures::getTeamName($teamIdentifier));

            for ($i = 0; $i < self::PLAYER_COUNT_IN_TEAM; $i++) {
                $player = new Player();
                $player->setFirstname(RandomHelper::getFirstname());
                $player->setLastname(RandomHelper::getLastname());
                $player->setBirthday(
                    RandomHelper::getBirthday($youthTeam[TeamFixtures::MIN_AGE], $youthTeam[TeamFixtures::MAX_AGE])
                );
                $player->setFoot(RandomHelper::getArrayValue(array_values(Player::$availableFoots)));
                $player->addTeam($team);

                $manager->persist($player);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TeamFixtures::class];
    }
}
