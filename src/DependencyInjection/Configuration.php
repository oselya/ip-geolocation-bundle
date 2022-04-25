<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ip_geolocation');
        $treeBuilder->getRootNode()
            ->children()
                ->integerNode('cache_ttl')->defaultValue(86400)->end()
                ->arrayNode('maxmind')
                    ->children()
                         ->scalarNode('city_path')->end()
                         ->integerNode('priority')->defaultValue(0)->end()
                    ->end()
                ->end()
                ->arrayNode('ip_api_com')
                    ->children()
                        ->scalarNode('access_key')->end()
                        ->integerNode('priority')->defaultValue(1)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}