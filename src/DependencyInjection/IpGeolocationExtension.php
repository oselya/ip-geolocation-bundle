<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class IpGeolocationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('oselya.ip_geolocation.cache_ttl', intval($config['cache_ttl']));

        if (isset($config['ip_api_com']['access_key'])) {
            $container->setParameter(
                'oselya.ip_geolocation.ip_api_com.access_key',
                strval($config['ip_api_com']['access_key'])
            );
            $container->setParameter(
                'oselya.ip_geolocation.ip_api_com.priority',
                intval($config['ip_api_com']['priority'])
            );
        }

        if (isset($config['maxmind']['city_path'])) {
            $container->setParameter(
                'oselya.ip_geolocation.maxmind.city_path',
                strval($config['maxmind']['city_path'])
            );
            $container->setParameter(
                'oselya.ip_geolocation.maxmind.priority',
                intval($config['maxmind']['priority'])
            );
        }
    }
}