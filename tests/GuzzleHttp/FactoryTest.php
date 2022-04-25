<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\GuzzleHttp;

use GuzzleHttp\ClientInterface;
use Oselya\IpGeolocationBundle\GuzzleHttp\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function test()
    {
        $obj = Factory::createClient();

        self::assertInstanceOf(ClientInterface::class, $obj);
    }
}
