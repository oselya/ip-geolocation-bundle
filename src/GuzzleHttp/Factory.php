<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\GuzzleHttp;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Factory
{
    public static function createClient(): ClientInterface
    {
        return new Client([
            'connect_timeout' => 1,
            'timeout' => 1,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'oselya/1.0',
            ]
        ]);
    }
}