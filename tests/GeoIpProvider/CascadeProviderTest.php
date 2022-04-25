<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\GeoIpProvider;

use Oselya\IpGeolocationBundle\Cache\Factory;
use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\GeoIpProvider\CascadeProvider;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIp2Provider;
use Oselya\IpGeolocationBundle\GeoIpProvider\IpApiComProvider;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;
use PHPUnit\Framework\TestCase;

class CascadeProviderTest extends TestCase
{
    public function testLookupWithProviders(): void
    {
        $ip = new Ip('128.0.0.1');

        $location = $this->createMock(Location::class);
        $ipApi = $this->createPartialMock(IpApiComProvider::class, ['ipLookup']);
        $ipApi->expects(self::exactly(1))
            ->method('ipLookup')
            ->willReturnMap([
                [$ip, $location],
            ]);

        $geoIp2 = $this->createPartialMock(GeoIp2Provider::class, ['ipLookup']);
        $geoIp2->expects(self::once())
            ->method('ipLookup')
            ->willThrowException(NotFoundException::fromIp($ip));

        $provider = (new CascadeProvider(Factory::create(-1)))->addProvider($ipApi, 1)->addProvider($geoIp2, 0);

        self::assertSame($location, $provider->ipLookup($ip));
    }

    public function testNotFound(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Location for "128.0.0.1" not found.');

        (new CascadeProvider(Factory::create(-1)))->ipLookup(new Ip('128.0.0.1'));
    }
}
