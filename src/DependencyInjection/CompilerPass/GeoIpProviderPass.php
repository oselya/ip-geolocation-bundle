<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\DependencyInjection\CompilerPass;

use GeoIp2\Database\Reader;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIp2Provider;
use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIpProviderInterface;
use Oselya\IpGeolocationBundle\GeoIpProvider\IpApiComProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class GeoIpProviderPass implements CompilerPassInterface
{
    private const PROVER_TAG = 'oselya.ip_geolocation_provider';

    public function process(ContainerBuilder $container)
    {
        $cascadeProvider = $container->getDefinition(GeoIpProviderInterface::class);

        $this->registerGeoIp2Provider($container);
        $this->registerIpApiComProvider($container);

        foreach ($container->findTaggedServiceIds(self::PROVER_TAG) as $id => $tags) {
            $cascadeProvider->addMethodCall(
                'addProvider',
                [new Reference($id), $this->getProviderPriority($id, $container)]
            );
        }
    }

    private function registerIpApiComProvider(ContainerBuilder $container): void
    {
        if ($container->hasParameter('oselya.ip_geolocation.ip_api_com.access_key')) {
            $ipApiComProvider = new Definition(
                IpApiComProvider::class,
                [
                    '%oselya.ip_geolocation.ip_api_com.access_key%',
                    $container->getDefinition('oselya.ip_geolocation.http_client'),
                ]
            );

            $ipApiComProvider->addTag(self::PROVER_TAG);

            $container->setDefinition(IpApiComProvider::class, $ipApiComProvider);
        }
    }

    private function registerGeoIp2Provider(ContainerBuilder $container): void
    {
        if ($container->hasParameter('oselya.ip_geolocation.maxmind.city_path')) {
            $container->setDefinition(
                Reader::class,
                new Definition(Reader::class, ['%oselya.ip_geolocation.maxmind.city_path%'])
            );

            $geoIp2Provider = new Definition(GeoIp2Provider::class, [$container->getDefinition(Reader::class)]);
            $geoIp2Provider->addTag(self::PROVER_TAG);

            $container->setDefinition(GeoIp2Provider::class, $geoIp2Provider);
        }
    }

    private function getProviderPriority(string $id, ContainerBuilder $container): int
    {
        return match ($id) {
            GeoIp2Provider::class => $container->getParameter('oselya.ip_geolocation.maxmind.priority'),
            IpApiComProvider::class => $container->getParameter('oselya.ip_geolocation.ip_api_com.priority'),
            default => random_int(2, 10),
        };
    }
}