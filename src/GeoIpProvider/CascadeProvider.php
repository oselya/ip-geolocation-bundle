<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\GeoIpProvider;

use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Throwable;

class CascadeProvider extends AbstractProvider
{
    private const CACHE_KEY_PATTERN = 'oselya_geoip_%s';

    public function __construct(private readonly CacheInterface $cache, private array $providers = [])
    {
    }

    public function addProvider(GeoIpProviderInterface $provider, int $priority): self
    {
        $this->providers[$priority] = $provider;

        ksort($this->providers);

        return $this;
    }

    protected function lookup(Ip $ip): Location
    {
        return $this->cache->get($this->getCacheKey($ip), function (ItemInterface $item) use ($ip) {
            foreach ($this->providers as $provider) {
                try {
                    return $provider->ipLookup($ip);
                } catch (Throwable) {
                    continue;
                }
            }

            throw NotFoundException::fromIp($ip);
        });
    }

    private function getCacheKey(Ip $ip): string
    {
        return sprintf(self::CACHE_KEY_PATTERN, $ip);
    }
}