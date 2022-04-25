<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\ValueObject;

use Oselya\IpGeolocationBundle\ValueObject\Continent;
use Oselya\IpGeolocationBundle\ValueObject\Country;
use Oselya\IpGeolocationBundle\ValueObject\Location;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    public function test()
    {
        $country = new Country('UA');
        $continent = new Continent('EU');
        $lat = 46.4888;
        $long = 30.7474;

        $obj = new Location($lat, $long, $continent, $country);

        self::assertEquals($lat, $obj->getLatitude());
        self::assertEquals($long, $obj->getLongitude());
        self::assertEquals($continent, $obj->getContinent());
        self::assertEquals($country, $obj->getCountry());
        self::assertEquals('Latitude: 46.488800; Longitude: 30.747400; Continent: EU; Country: UA', strval($obj));
    }
}
