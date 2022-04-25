<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\Cache;

use Oselya\IpGeolocationBundle\Cache\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;

class FactoryTest extends TestCase
{
    public function test()
    {
        self::assertInstanceOf(ArrayAdapter::class, Factory::create(-1));
        self::assertInstanceOf(ChainAdapter::class, Factory::create(30));
    }
}
