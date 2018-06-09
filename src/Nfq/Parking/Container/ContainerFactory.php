<?php

namespace Nfq\Parking\Container;

use Nfq\Parking\DependencyInjection\NfqParkingExtension;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class ContainerFactory
{
    /**
     * @param $filename
     * @param bool $isDebug
     *
     * @return \ParkingContainer
     */
    public static function create($filename, $isDebug = false)
    {
        $containerConfigCache = new ConfigCache($filename, $isDebug);

        if (!$containerConfigCache->isFresh()) {
            $containerBuilder = new ContainerBuilder();
            $extension = new NfqParkingExtension();
            $containerBuilder->registerExtension($extension);
            $containerBuilder->loadFromExtension($extension->getAlias());
            $containerBuilder->compile();

            $dumper = new PhpDumper($containerBuilder);
            $containerConfigCache->write(
                $dumper->dump(['class' => 'ParkingContainer']),
                $containerBuilder->getResources()
            );
        }

        require_once $filename;

        return new \ParkingContainer();
    }
}