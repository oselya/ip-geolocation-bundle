<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\ValueObject;

use Generator;
use InvalidArgumentException;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use PHPUnit\Framework\TestCase;

class IpTest extends TestCase
{
    /**
     * @dataProvider successDataProvider
     *
     * @param string $value
     * @param bool $isIpV4
     * @param bool $isIpV6
     * @param bool $isPrivate
     */
    public function testSuccess(string $value, bool $isIpV4, bool $isIpV6, bool $isPrivate): void
    {
        $ip = new Ip($value);

        self::assertEquals($value, $ip);
        self::assertSame($isIpV4, $ip->isV4(), 'is IPV4 ' . $ip);
        self::assertSame($isIpV6, $ip->isV6(), 'is IPV6 ' . $ip);
        self::assertSame($isPrivate, $ip->isPrivate(), 'is private IP ' . $ip);
    }

    public function successDataProvider(): Generator
    {
        yield ['127.0.0.1', true, false, true];
        yield ['192.168.254.254', true, false, true];
        yield ['10.255.0.3', true, false, true];
        yield ['172.16.255.255', true, false, true];
        yield ['172.31.255.255', true, false, true];
        yield ['192.169.255.255', true, false, false];
        yield ['9.255.0.255', true, false, false];
        yield ['67.22.74.181', true, false, false];
        yield ['109.86.51.55', true, false, false];
        yield ['109.42.112.5', true, false, false];
        yield ['81.185.175.46', true, false, false];

        yield ['2409:4073:f:11b9:817a:81ea:a128:1955', false, true, false];
        yield ['2401:4900:1a8d:3eaf:84b5:5538:efd:5586', false, true, false];
        yield ['2409:4063:230c:e172:dd1a:4c74:de7c:cc21', false, true, false];
        yield ['2601:240:a:1bc6:9c39:66f0:ced8:d643', false, true, false];
        yield ['fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', false, true, true];
        yield ['fc10::1', false, true, true];
    }

    public function testFailed(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('"test" is not a valid IP address.');

        new Ip('test');
    }
}