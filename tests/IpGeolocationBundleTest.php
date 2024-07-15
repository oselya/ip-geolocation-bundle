<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests;

use Oselya\IpGeolocationBundle\DependencyInjection\CompilerPass\GeoIpProviderPass;
use Oselya\IpGeolocationBundle\IpGeolocationBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IpGeolocationBundleTest extends TestCase
{
    public function test(): void
    {
        $bundle = new IpGeolocationBundle();

        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf(GeoIpProviderPass::class));

        $bundle->build($containerBuilder);
    }
}