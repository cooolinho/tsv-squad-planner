<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Helper\RandomHelper;
use App\Helper\YouthClassHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    private const PLAYER_COUNT_IN_TEAM = 25;

    public function load(ObjectManager $manager): void
    {
        foreach (YouthClassHelper::$youthTeams as $teamIdentifier => $youthTeam) {
            $team = $this->getReference($teamIdentifier);

            for ($i = 0; $i < self::PLAYER_COUNT_IN_TEAM; $i++) {
                $player = new Player();
                $player->setFirstname(RandomHelper::getFirstname());
                $player->setLastname(RandomHelper::getLastname());
                $player->setBirthday(
                    RandomHelper::getBirthday(
                        $youthTeam[YouthClassHelper::MIN_AGE] ?? RandomHelper::DEFAULT_MIN_AGE,
                        $youthTeam[YouthClassHelper::MAX_AGE]
                    )
                );
                $player->setFoot(RandomHelper::getArrayValue(array_values(Player::$availableFoots)));
                $player->setTeam($team);

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
