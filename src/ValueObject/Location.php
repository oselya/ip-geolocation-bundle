<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\ValueObject;

use Stringable;

class Location implements Stringable
{
    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly Continent $continent,
        private readonly ?Country $country
    ) {
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getContinent(): Continent
    {
        return $this->continent;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function __toString(): string
    {
        return sprintf(
            'Latitude: %f; Longitude: %f; Continent: %s; Country: %s',
            $this->latitude,
            $this->longitude,
            $this->continent,
            $this->country,
        );
    }
}