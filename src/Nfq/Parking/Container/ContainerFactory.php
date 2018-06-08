<?php

namespace Nfq\Parking\Container;


use Nfq\Parking\DependencyInjection\NfqParkingExtension;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerFactory
{
    /**
     * @return ContainerInterface
     */
    public static function create($filename)
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new NfqParkingExtension();
        $containerBuilder->registerExtension($extension);
        $containerBuilder->loadFromExtension($extension->getAlias());
        $containerBuilder->compile();

        $dumper = new PhpDumper($containerBuilder);

        file_put_contents($filename, $dumper->dump(['class' => 'ParkingContainer']));

        require_once $filename;

        return new \ParkingContainer();
    }
}