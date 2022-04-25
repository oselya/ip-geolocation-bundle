<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\GeoIpProvider;

use InvalidArgumentException;
use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;

abstract class AbstractProvider implements GeoIpProviderInterface
{
    public function ipLookup(Ip $ip): Location
    {
        if ($ip->isPrivate()) {
            throw new InvalidArgumentException(sprintf('"%s" should be public IP address.', $ip));
        }

        return $this->lookup($ip);
    }

    /**
     * @throws NotFoundException
     */
    abstract protected function lookup(Ip $ip): Location;
}