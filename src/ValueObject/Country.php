<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\ValueObject;

use InvalidArgumentException;
use Stringable;
use Symfony\Component\Intl\Countries;

class Country implements Stringable
{
    public function __construct(private readonly string $value)
    {
        if (!Countries::alpha3CodeExists($value) && !Countries::exists($this->value)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid ISO country code.', $value));
        }
    }

    public function isAlpha3(): bool
    {
        return 3 === mb_strlen(trim($this->value));
    }

    public function isAlpha2(): bool
    {
        return 2 === mb_strlen(trim($this->value));
    }

    public function getAlpha3Code(): string
    {
        if ($this->isAlpha2()) {
            return Countries::getAlpha3Code($this->value);
        }

        return $this->value;
    }

    public function getAlpha2Code(): string
    {
        if ($this->isAlpha3()) {
            return Countries::getAlpha2Code($this->value);
        }

        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}