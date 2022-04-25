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