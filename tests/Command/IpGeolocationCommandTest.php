<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\Command;

use Oselya\IpGeolocationBundle\Command\IpGeolocationCommand;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIpProviderInterface;
use Oselya\IpGeolocationBundle\ValueObject\Continent;
use Oselya\IpGeolocationBundle\ValueObject\Country;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class IpGeolocationCommandTest extends TestCase
{
    public function testWithoutIP(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "ip").');

        $provider = $this->createMock(GeoIpProviderInterface::class);
        $command = new IpGeolocationCommand($provider);
        $command->setName('app:ip:location');
        $tester = new CommandTester($command);
        $tester->execute([]);
    }

    public function testOk(): void
    {
        $provider = $this->createMock(GeoIpProviderInterface::class);
        $provider->expects($this->once())
            ->method('ipLookup')
            ->with(new Ip('128.0.0.1'))
            ->willReturn(
                new Location(46.4888, 30.7474, new Continent('EU'), new Country('UA'))
            );

        $command = new IpGeolocationCommand($provider);
        $command->setName('app:ip:location');
        $tester = new CommandTester($command);
        $tester->execute(['ip' => '128.0.0.1']);

        $result = <<<'CLI_RESULT'
+-----------+-----------+---------+----------+-----------+
| IP        | Continent | Country | Latitude | Longitude |
+-----------+-----------+---------+----------+-----------+
| 128.0.0.1 | EU        | UA      | 46.4888  | 30.7474   |
+-----------+-----------+---------+----------+-----------+

CLI_RESULT;

        self::assertEquals(
            $result,
            $tester->getDisplay()
        );
    }
}