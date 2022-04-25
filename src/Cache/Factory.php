<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Cache;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class Factory
{
    public static function create(int $ttl): CacheInterface
    {
        if (-1 === $ttl) {
            return new ArrayAdapter();
        }

        return new ChainAdapter([
            new ArrayAdapter(),
            new FilesystemAdapter(namespace: 'ip-geolocation', defaultLifetime: $ttl),
        ]);
    }
}