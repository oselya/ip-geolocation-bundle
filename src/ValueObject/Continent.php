<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\ValueObject;

use Stringable;

class Continent implements Stringable
{
    public function __construct(private readonly string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}