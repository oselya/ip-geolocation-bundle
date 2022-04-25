<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\DependencyInjection;

use Oselya\IpGeolocationBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testDefault(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), []);

        self::assertEquals(
            [
                'cache_ttl' => 86400,
            ],
            $config
        );
    }

    public function testWithConfiguration(): void
    {
        $input = [
            'cache_ttl' => 60,
            'maxmind' => [
                'city_path' => 'test-path',
                'priority' => 33,
            ],
            'ip_api_com' => [
                'access_key' => 'test-access-key',
            ],
        ];

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [$input]);

        self::assertEquals(
            [
                'cache_ttl' => 60,
                'maxmind' => [
                    'city_path' => 'test-path',
                    'priority' => 33,
                ],
                'ip_api_com' => [
                    'access_key' => 'test-access-key',
                    'priority' => 1,
                ],
            ],
            $config
        );
    }
}