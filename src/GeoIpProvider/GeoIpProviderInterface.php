<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\GeoIpProvider;

use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;

interface GeoIpProviderInterface
{
    public function ipLookup(Ip $ip): Location;
}