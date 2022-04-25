<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\DependencyInjection\CompilerPass;

use GuzzleHttp\ClientInterface;
use Oselya\IpGeolocationBundle\DependencyInjection\CompilerPass\GeoIpProviderPass;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIp2Provider;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIpProviderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class GeoIpProviderPassTest extends TestCase
{
    public function test(): void
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new GeoIpProviderPass());

        $container->setDefinition(
            GeoIpProviderInterface::class,
            (new Definition(GeoIpProviderInterface::class))->setPublic(true)
        );
        $container->setDefinition(
            'oselya.ip_geolocation.http_client',
            (new Definition(ClientInterface::class))->setPublic(true)
        );
        $container->setDefinition(
            GeoIp2Provider::class,
            (new Definition(GeoIp2Provider::class))->setPublic(true)
        );

        $container->setParameter('oselya.ip_geolocation.ip_api_com.access_key', 'access_key');
        $container->setParameter('oselya.ip_geolocation.ip_api_com.priority', 1);
        $container->setParameter('oselya.ip_geolocation.maxmind.city_path', 'city_path');
        $container->setParameter('oselya.ip_geolocation.maxmind.priority', 0);

        $container->compile();

        self::assertEquals(1, 1);
    }
}