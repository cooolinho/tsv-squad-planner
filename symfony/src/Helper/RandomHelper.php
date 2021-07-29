<?php

namespace App\Helper;

class RandomHelper
{
    public const DEFAULT_MIN_AGE = 1;
    public const DEFAULT_MAX_AGE = 99;

    public static array $firstnames = [
        'Maxwell', 'Conner', 'Farhan', 'Alan', 'Harley', 'Erik', 'Jeremiah', 'Charles', 'Robbie', 'Omar',
        'Muhamet', 'Luke', 'Johannes', 'Luca Fynn', 'Calvin', 'Tom', 'Levin', 'Julian', 'Mikka', 'Janis', 'Paul',
        'Yousef', 'Magnus', 'Tarek', 'Jan-Niklas', 'Luca', 'Konrad', 'Benjamin', 'Oskar', 'Darius', 'Erik', 'Finn',
        'Josef', 'Jonte',
    ];

    public static array $lastnames = [
        'Lawrence', 'Miles', 'Tran', 'Wong', 'Cooper', 'Carey', 'Jackson', 'Cunningham', 'Cunningham', 'Campbell',
        'Lueken', 'Kebianyor', 'Adomat', 'Hellbusch', 'Behrends', 'Eggers', 'Bornhorn', 'Wahl', 'Stauf', 'Nordmann',
        'Haase', 'Lüning', 'Klingberg', 'Spielberger', 'Norton', 'Emke-Hormes', 'Frenzel', 'Becker', 'Thien', 'Schütte',
        'Debiel', 'Seeger',
    ];

    public static function getFullname(): string
    {
        return self::getFirstname() . ' ' . self::getLastname();
    }

    public static function getFirstname(): string
    {
        return self::getArrayValue(self::$lastnames);
    }

    public static function getArrayValue(array $arr): ?string
    {
        if (empty($arr)) {
            return null;
        }

        return $arr[random_int(0, count($arr) - 1)];
    }

    public static function getLastname(): string
    {
        return self::getArrayValue(self::$firstnames);
    }

    public static function getBirthday(
        int $minAge = self::DEFAULT_MIN_AGE,
        int $maxAge = self::DEFAULT_MAX_AGE
    ): \DateTime
    {
        $minDate = self::getDateSubstractByYears($maxAge);
        $maxDate = self::getDateSubstractByYears($minAge);

        $randomDate = date('Y-m-d', random_int($minDate->getTimestamp(), $maxDate->getTimestamp()));

        return new \DateTime($randomDate);
    }

    public static function getDateSubstractByYears(int $years = 0): \DateTime
    {
        return (new \DateTime())->modify('-' . $years . ' year');
    }

    /**
     * @throws \Exception
     */
    public static function getInt($min = 1, $max = 100): int
    {
        return random_int($min, $max);
    }
}
