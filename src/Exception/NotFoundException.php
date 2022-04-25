<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Exception;

use Exception;
use Oselya\IpGeolocationBundle\ValueObject\Ip;

class NotFoundException extends Exception
{
    public static function fromIp(Ip $ip): self
    {
        return new self(sprintf('Location for "%s" not found.', $ip));
    }
}