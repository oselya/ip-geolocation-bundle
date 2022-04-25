<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\ValueObject;

use InvalidArgumentException;
use Stringable;

/**
 * @see https://en.wikipedia.org/wiki/IP_address
 */
class Ip implements Stringable
{
    public function __construct(private readonly string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid IP address.', $value));
        }
    }

    /**
     * @see https://en.wikipedia.org/wiki/IPv4
     */
    public function isV4(): bool
    {
        return boolval(filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4));
    }

    /**
     * @see https://en.wikipedia.org/wiki/IPv6
     */
    public function isV6(): bool
    {
        return boolval(filter_var($this->value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6));
    }

    public function isPrivate(): bool
    {
        return null === filter_var(
                $this->value,
                FILTER_VALIDATE_IP,
                FILTER_NULL_ON_FAILURE
                | FILTER_FLAG_IPV4
                | FILTER_FLAG_IPV6
                | FILTER_FLAG_NO_PRIV_RANGE // The value must not be within a private range
                | FILTER_FLAG_NO_RES_RANGE // The value must not be within a reserved range
            );
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
