<?php

namespace App\Controller\Admin\Fields;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AddressFields
{
    public static function addAll(): \Generator
    {
        yield self::getStreet();
        yield self::getStreetNr();
        yield self::getZip();
        yield self::getCity();
    }

    public static function getStreet(): TextField
    {
        return TextField::new('street', 'player.street.label');
    }

    public static function getStreetNr(): TextField
    {
        return TextField::new('streetNr', 'player.street_nr.label');
    }

    public static function getZip(): TextField
    {
        return TextField::new('zip', 'player.zip.label');
    }

    public static function getCity(): TextField
    {
        return TextField::new('city', 'player.city.label');
    }

    public static function getPhone(): TextField
    {
        return TextField::new('phone', 'player.phone.label');
    }
}
