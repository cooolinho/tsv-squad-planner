<?php

namespace App\Helper;

class RandomHelper
{
    public static array $firstnames = [
        'Maxwell', 'Conner', 'Farhan', 'Alan', 'Harley', 'Erik', 'Jeremiah', 'Charles', 'Robbie', 'Omar'
    ];

    public static array $lastnames = [
        'Lawrence', 'Miles', 'Tran', 'Wong', 'Cooper', 'Carey', 'Jackson', 'Cunningham', 'Cunningham', 'Campbell'
    ];

    public static function getFirstname(): string
    {
        return self::getArrayValue(self::$lastnames);
    }

    public static function getLastname(): string
    {
        return self::getArrayValue(self::$firstnames);
    }

    public static function getFullname(): string
    {
        return self::getFirstname() . ' ' . self::getLastname();
    }

    public static function getBirthday(int $minAge = 1, int $maxAge = 99): \DateTime
    {
        $minDate = (new \DateTime())->modify('-' . $maxAge . ' year');
        $maxDate = (new \DateTime())->modify('-' . $minAge . ' year');

        $randomDate = date('Y-m-d', random_int($minDate->getTimestamp(), $maxDate->getTimestamp()));

        return new \DateTime($randomDate);
    }

    public static function getArrayValue(array $arr)
    {
        if (empty($arr)) {
            return null;
        }

        return $arr[random_int(0, count($arr) - 1)];
    }
}
