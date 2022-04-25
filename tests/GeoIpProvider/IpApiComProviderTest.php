<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Tests\GeoIpProvider;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Psr7\Response;
use Oselya\IpGeolocationBundle\Exception\NotFoundException;
use Oselya\IpGeolocationBundle\GeoIpProvider\IpApiComProvider;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use InvalidArgumentException as BaseInvalidArgumentException;

class IpApiComProviderTest extends TestCase
{
    private const API_KEY = 'qwerty';
    private MockObject $http;
    private IpApiComProvider $provider;

    public function testLookupGuzzleException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Location for "128.0.0.1" not found.');

        $this->http->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'http://api.ipapi.com/api/128.0.0.1',
                [
                    'query' => [
                        'access_key' => self::API_KEY,
                        'fields' => 'continent_code,country_code,latitude,longitude',
                    ]
                ]
            )
            ->willThrowException(new InvalidArgumentException());

        $this->provider->ipLookup(new Ip('128.0.0.1'));
    }

    public function testSuccess(): void
    {
        $json = <<<'JSON'
{
    "longitude": 24.57537078857422,
    "latitude": 48.34244918823242,
    "country_code": "UA",
    "continent_code": "EU"
}
JSON;

        $this->http->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'http://api.ipapi.com/api/92.253.204.162',
                [
                    'query' => [
                        'access_key' => self::API_KEY,
                        'fields' => 'continent_code,country_code,latitude,longitude',
                    ]
                ]
            )
            ->willReturn(new Response(HttpResponse::HTTP_OK, [], $json));

        $location = $this->provider->ipLookup(new Ip('92.253.204.162'));

        self::assertEquals('UA', $location->getCountry());
        self::assertEquals('EU', $location->getContinent());
        self::assertEquals(48.34244918823242, $location->getLatitude());
        self::assertEquals(24.57537078857422, $location->getLongitude());
    }

    public function testInvalidPayload(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Location for "92.253.204.162" not found.');

        $this->http->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'http://api.ipapi.com/api/92.253.204.162',
                [
                    'query' => [
                        'access_key' => self::API_KEY,
                        'fields' => 'continent_code,country_code,latitude,longitude',
                    ]
                ]
            )
            ->willReturn(new Response(HttpResponse::HTTP_OK, [], '{}'));

        $this->provider->ipLookup(new Ip('92.253.204.162'));
    }

    public function testPrivateIP(): void
    {
        $this->expectException(BaseInvalidArgumentException::class);
        $this->expectExceptionMessage('"fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff" should be public IP address.');

        $this->provider->ipLookup(new Ip('fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = $this->createMock(ClientInterface::class);
        $this->provider = new IpApiComProvider(self::API_KEY, $this->http);
    }
}
