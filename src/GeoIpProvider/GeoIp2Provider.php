<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\GeoIpProvider;

use GeoIp2\ProviderInterface;
use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\ValueObject\Continent;
use Oselya\IpGeolocationBundle\ValueObject\Country;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;

/**
 * @see https://www.maxmind.com/en/geoip2-databases
 */
class GeoIp2Provider extends AbstractProvider
{
    public function __construct(private readonly ProviderInterface $provider)
    {
    }

    protected function lookup(Ip $ip): Location
    {
        $record = $this->provider->city(strval($ip));

        if (!isset($record->continent->code) || !isset($record->location->latitude) || !isset($record->location->longitude)) {
            throw NotFoundException::fromIp($ip);
        }

        $country = null;

        if (isset($record->country->isoCode)) {
            $country = new Country(strval($record->country->isoCode));
        }

        return new Location(
            floatval($record->location->latitude),
            floatval($record->location->longitude),
            new Continent(strval($record->continent->code)),
            $country
        );
    }
}