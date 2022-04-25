<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\ValueObject;

use Generator;
use InvalidArgumentException;
use Oselya\IpGeolocationBundle\ValueObject\Country;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    /**
     * @dataProvider alpha2Provider
     */
    public function testAlpha2(string $code, string $alpha3): void
    {
        $obj = new Country($code);

        self::assertTrue($obj->isAlpha2());
        self::assertFalse($obj->isAlpha3());
        self::assertEquals($code, strval($obj));
        self::assertEquals($alpha3, $obj->getAlpha3Code());
        self::assertEquals($code, $obj->getAlpha2Code());
    }

    public function alpha2Provider(): Generator
    {
        yield ['CA', 'CAN'];
        yield ['IT', 'ITA'];
        yield ['JP', 'JPN'];
        yield ['UA', 'UKR'];
    }

    /**
     * @dataProvider alpha3Provider
     */
    public function testAlpha3(string $code, string $alpha2): void
    {
        $obj = new Country($code);

        self::assertTrue($obj->isAlpha3());
        self::assertFalse($obj->isAlpha2());
        self::assertEquals($code, strval($obj));
        self::assertEquals($alpha2, $obj->getAlpha2Code());
        self::assertEquals($code, $obj->getAlpha3Code());
    }

    public function alpha3Provider(): Generator
    {
        yield ['VAT', 'VA'];
        yield ['ZWE', 'ZW'];
        yield ['SSD', 'SS'];
        yield ['NLD', 'NL'];
    }

    public function testInvalidValue(): void
    {
        $this->expectExceptionMessage('"TEST" is not a valid ISO country code.');
        $this->expectException(InvalidArgumentException::class);

        new Country('TEST');
    }
}
