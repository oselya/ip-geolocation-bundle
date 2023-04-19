Symfony IP geolocation bundle
=============================
[![License][license-image]][license-link] ![workflow](https://github.com/oselya/ip-geolocation-bundle/actions/workflows/php.yml/badge.svg)

Install

```shell
composer req oselya/ip-geolocation-bundle
```

Before we get started, there is a small amount of configuration needed

```yml
# app/config/ip_geolocation.yaml

ip_geolocation:
  cache_ttl: -1
  maxmind:
    city_path: 'GeoLite2-City.mmdb'
  ip_api_com:
    access_key: 'qwerty'
```

Cli command

```shell
$ bin/console app:ip:location 92.253.204.162
+----------------+-----------+---------+-----------------+-----------------+
| IP             | Continent | Country | Latitude        | Longitude       |
+----------------+-----------+---------+-----------------+-----------------+
| 92.253.204.162 | EU        | UA      | 48.342449188232 | 24.575370788574 |
+----------------+-----------+---------+-----------------+-----------------+
```

Service

```php
<?php

declare(strict_types=1);

namespace Oselya\IpGeolocationBundle\Command;

use Oselya\IpGeolocationBundle\GeoIpProvider\GeoIpProviderInterface;
use Oselya\IpGeolocationBundle\ValueObject\Ip;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IpGeolocationCommand extends Command
{
    public function __construct(private readonly GeoIpProviderInterface $provider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:ip:location')
            ->addArgument('ip', InputArgument::REQUIRED, 'The IP address.')
            ->setDescription('This command allows you to lookup location of IP addresses.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $location = $this->provider->ipLookup(new Ip($input->getArgument('ip')));

        $table = new Table($output);
        $table
            ->setHeaders(['IP', 'Continent', 'Country', 'Latitude', 'Longitude'])
            ->setRows([
                [
                    $input->getArgument('ip'),
                    $location->getContinent(),
                    $location->getCountry(),
                    $location->getLatitude(),
                    $location->getLongitude(),
                ],
            ]);
        $table->render();

        return Command::SUCCESS;
    }
}
```

[license-link]: https://github.com/oselya/ip-geolocation-bundle/blob/main/LICENSE
[license-image]: https://img.shields.io/dub/l/vibe-d.svg