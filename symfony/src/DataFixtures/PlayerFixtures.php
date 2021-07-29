<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\AddressFixtureTrait;
use App\Entity\Player;
use App\Helper\RandomHelper;
use App\Helper\YouthClassHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlayerFixtures extends Fixture implements DependentFixtureInterface
{
    use AddressFixtureTrait;

    private const PLAYER_COUNT_IN_TEAM = 25;

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach (YouthClassHelper::$youthTeams as $teamIdentifier => $youthTeam) {
            $team = $this->getReference($teamIdentifier);

            for ($i = 0; $i < self::PLAYER_COUNT_IN_TEAM; $i++) {
                $player = new Player();
                $club = $this->getReference(RandomHelper::getArrayValue(ClubFixtures::DEMO_CLUBS));

                // Personal Information
                $player->setFirstname(RandomHelper::getFirstname());
                $player->setLastname(RandomHelper::getLastname());

                $this->addDemoAddressData($player);

                $player->setStreet('Demo-Street');
                $player->setStreetNr(RandomHelper::getInt());
                $player->setZip('12345');
                $player->setCity('Democity');
                $player->setPhone('0123456789');
                $player->setBirthday(
                    RandomHelper::getBirthday(
                        $youthTeam[YouthClassHelper::MIN_AGE] ?? RandomHelper::DEFAULT_MIN_AGE,
                        $youthTeam[YouthClassHelper::MAX_AGE]
                    )
                );

                // Attributes
                $player->setFoot(RandomHelper::getArrayValue(array_values(Player::$footChoices)));

                // Clothing
                $player->setTrainingsJacket(RandomHelper::getArrayValue(array_values(Player::$clothingFitSizeChoices)));
                $player->setTrainingsTrousers(RandomHelper::getArrayValue(array_values(Player::$clothingFitSizeChoices)));
                $player->setWarmUpShirt(RandomHelper::getArrayValue(array_values(Player::$clothingFitSizeChoices)));
                $player->setWarmUpSweater(RandomHelper::getArrayValue(array_values(Player::$clothingFitSizeChoices)));

                $player->setTeam($team);
                $player->setClub($club);

                $manager->persist($player);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TeamFixtures::class, ClubFixtures::class];
    }
}
