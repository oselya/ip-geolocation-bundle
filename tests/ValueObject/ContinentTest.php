<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\ValueObject;

use Generator;
use Oselya\IpGeolocationBundle\ValueObject\Continent;
use PHPUnit\Framework\TestCase;

class ContinentTest extends TestCase
{
    /**
     * @dataProvider successDataProvider
     */
    public function testSuccess(string $value): void
    {
        $obj = new Continent($value);

        self::assertEquals($value, strval($obj));
    }

    public function successDataProvider(): Generator
    {
        yield ['EU'];
    }
}
