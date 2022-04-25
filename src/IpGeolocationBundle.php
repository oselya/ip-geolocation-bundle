<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle;

use Oselya\IpGeolocationBundle\DependencyInjection\CompilerPass\GeoIpProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IpGeolocationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GeoIpProviderPass());
    }

    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}