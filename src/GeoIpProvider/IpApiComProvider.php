<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\GeoIpProvider;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\ValueObject\Continent;
use Oselya\IpGeolocationBundle\ValueObject\Country;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Oselya\IpGeolocationBundle\ValueObject\Location;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @see https://ipapi.com/documentation
 */
class IpApiComProvider extends AbstractProvider
{
    private const HOST = 'http://api.ipapi.com/api/';

    public function __construct(private readonly string $apiKey, private readonly ClientInterface $client)
    {
    }

    protected function lookup(Ip $ip): Location
    {
        try {
            $resp = $this->client->request(
                Request::METHOD_GET,
                self::HOST . $ip,
                [
                    RequestOptions::QUERY => [
                        'access_key' => $this->apiKey,
                        'fields' => implode(',', [
                            'continent_code',
                            'country_code',
                            'latitude',
                            'longitude',
                        ]),
                    ],
                ]
            );

            $data = json_decode($resp->getBody()->getContents(), true);

            if (!is_array($data) ||
                (!array_key_exists('continent_code', $data)
                && !array_key_exists('latitude', $data)
                && !array_key_exists('longitude', $data))) {
                throw new RuntimeException();
            }

            $country = null;

            if (array_key_exists('country_code', $data)) {
                $country = new Country(strval($data['country_code']));
            }

            return new Location(
                floatval($data['latitude']),
                floatval($data['longitude']),
                new Continent(strval($data['continent_code'])),
                $country
            );
        } catch (Throwable) {
        }

        throw NotFoundException::fromIp($ip);
    }
}