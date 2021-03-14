<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public const YOUTH = 'Youth';
    public const MIN_AGE = 'minAge';
    public const MAX_AGE = 'maxAge';

    public static array $youthTeams = [
        'A' => [
            self::MIN_AGE => '18',
            self::MAX_AGE => '19',
        ],
        'B' => [
            self::MIN_AGE => '16',
            self::MAX_AGE => '17',
        ],
        'C' => [
            self::MIN_AGE => '14',
            self::MAX_AGE => '15',
        ],
        'D' => [
            self::MIN_AGE => '12',
            self::MAX_AGE => '13',
        ],
        'E' => [
            self::MIN_AGE => '10',
            self::MAX_AGE => '11',
        ],
        'F' => [
            self::MIN_AGE => '8',
            self::MAX_AGE => '9',
        ],
        'G' => [
            self::MIN_AGE => '6',
            self::MAX_AGE => '7',
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach (array_keys(self::$youthTeams) as $teamIdentifier) {
            $team = new Team();
            $team->setName(self::getTeamName($teamIdentifier));
            $this->addReference($team->getName(), $team);

            $manager->persist($team);
        }

        $manager->flush();
    }

    public static function getTeamName(string $teamIdentifier): string
    {
        return $teamIdentifier . '-' . self::YOUTH;
    }
}
