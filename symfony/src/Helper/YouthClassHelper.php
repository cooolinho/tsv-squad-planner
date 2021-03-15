<?php

namespace App\Helper;

class YouthClassHelper
{
    public const A = 'A';
    public const B = 'B';
    public const C = 'C';
    public const D = 'D';
    public const E = 'E';
    public const F = 'F';
    public const G = 'G';

    public const MIN_AGE = 'minAge';
    public const MAX_AGE = 'maxAge';

    public static array $youthTeams = [
        self::A => [
            self::MIN_AGE => 18,
            self::MAX_AGE => 19,
        ],
        self::B => [
            self::MIN_AGE => 16,
            self::MAX_AGE => 17,
        ],
        self::C => [
            self::MIN_AGE => 14,
            self::MAX_AGE => 15,
        ],
        self::D => [
            self::MIN_AGE => 12,
            self::MAX_AGE => 13,
        ],
        self::E => [
            self::MIN_AGE => 10,
            self::MAX_AGE => 11,
        ],
        self::F => [
            self::MIN_AGE => 8,
            self::MAX_AGE => 9,
        ],
        self::G => [
            self::MAX_AGE => '7',
        ],
    ];

    public static function getClassByIdentifier(string $identifier): ?array
    {
        return self::$youthTeams[$identifier] ?? null;
    }

    public static function getMinAgeByYouthClass(string $identifier): \DateTime
    {
        return RandomHelper::getDateSubstractByYears(self::getMinAgeClassByIdentifier($identifier));
    }

    public static function getMinAgeClassByIdentifier(string $identifier): int
    {
        return self::$youthTeams[$identifier][self::MIN_AGE] ?? RandomHelper::DEFAULT_MIN_AGE;
    }

    public static function getMaxAgeByYouthClass(string $identifier): \DateTime
    {
        return RandomHelper::getDateSubstractByYears(self::getMaxAgeClassByIdentifier($identifier));
    }

    public static function getMaxAgeClassByIdentifier(string $identifier): int
    {
        return self::$youthTeams[$identifier][self::MAX_AGE] ?? RandomHelper::DEFAULT_MAX_AGE;
    }

    public static function getClassesForChoiceType(): array
    {
        $choices = [];

        foreach (self::$youthTeams as $identifier => $youthTeam) {
            $label = 'U' . $youthTeam[self::MAX_AGE];

            if (array_key_exists(self::MIN_AGE, $youthTeam)) {
                $label .= '/' . $youthTeam[self::MIN_AGE];
            }

            $choices[$label] = $identifier;
        }

        return $choices;
    }

    public static function getDateByYouthClass(
        string $youthClass,
        string $modifyYears = '+0',
        bool $min = true
    ): \DateTime
    {
        if (!$min) {
            $date = self::getMinAgeByYouthClass($youthClass);
        } else {
            $date = self::getMaxAgeByYouthClass($youthClass);
        }

        $date
            ->modify($modifyYears . ' year')
            ->setTime($min ? 0 : 23, $min ? 0 : 59, $min ? 0 : 59);

        return $date->setDate(date('Y', $date->getTimestamp()), $min ? 1 : 12, $min ? 1 : 31);
    }
}
