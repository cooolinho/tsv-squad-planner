<?php

namespace App\DataFixtures\Traits;

use App\Helper\RandomHelper;

trait AddressFixtureTrait
{
    /**
     * @throws \Exception
     */
    public function addDemoAddressData($obj): void
    {
        $obj->setStreet('Demo-Street');
        $obj->setStreetNr(RandomHelper::getInt());
        $obj->setZip('12345');
        $obj->setCity('Democity');
    }
}
