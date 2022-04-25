<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\GeoIpProvider;

use GeoIp2\Database\Reader;
use GeoIp2\Model\City;
use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIp2Provider;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use PHPUnit\Framework\TestCase;

class GeoIp2ProviderTest extends TestCase
{
    public function testSuccess(): void
    {
        $result = new City([
            'continent' => [
                'code' => 'EU',
            ],
            'location' => [
                'latitude' => 46.4888,
                'longitude' => 30.7474,
            ],
            'country' => [
                'iso_code' => 'UA',
            ]
        ]);

        $reader = $this->createMock(Reader::class);
        $reader->expects(self::once())
            ->method('city')
            ->with('128.0.0.1')
            ->willReturn($result);

        $provider = new GeoIp2Provider($reader);
        $location = $provider->ipLookup(new Ip('128.0.0.1'));

        self::assertEquals('UA', $location->getCountry());
        self::assertEquals('EU', $location->getContinent());
        self::assertEquals(46.4888, $location->getLatitude());
        self::assertEquals(30.7474, $location->getLongitude());
    }

    public function testNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Location for "128.0.0.1" not found.');

        $result = new City([
            'continent' => [],
            'location' => [],
        ]);

        $reader = $this->createMock(Reader::class);
        $reader->expects(self::once())
            ->method('city')
            ->with('128.0.0.1')
            ->willReturn($result);

        $provider = new GeoIp2Provider($reader);
        $provider->ipLookup(new Ip('128.0.0.1'));
    }
}
