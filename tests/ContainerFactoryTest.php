<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Nfq\Parking\Container\ContainerFactory;

class ContainerFactoryTest extends TestCase
{
    private $container = __DIR__ . '/../var/cache/containerCacheForTest.php';
    private $containerMeta = __DIR__ . '/../var/cache/containerCacheForTest.php.meta';

    public function test_debug_false_and_container_not_created(): void
    {
        $this->unlink_cache(true, true);

        $containerFactory = new ContainerFactory();
        $parkingContainer = $containerFactory::create($this->container);

        $this->assertInstanceOf(\ParkingContainer::class, $parkingContainer);

        $this->assertFileExists($this->container);
        $this->assertFileExists($this->containerMeta);
    }

    public function test_debug_false_and_container_created(): void
    {
        $this->unlink_cache(false, true);

        $containerFactory = new ContainerFactory();
        $parkingContainer = $containerFactory::create($this->container);

        $this->assertInstanceOf(\ParkingContainer::class, $parkingContainer);

        $this->assertFileExists($this->container);
        $this->assertFileNotExists($this->containerMeta);
    }

    public function test_debug_true(): void
    {
        $containerFactory = new ContainerFactory();
        $parkingContainer = $containerFactory::create($this->container, true);

        $this->assertInstanceOf(\ParkingContainer::class, $parkingContainer);

        $this->assertFileExists($this->container);
        $this->assertFileExists($this->containerMeta);
    }

    private function unlink_cache($container, $meta)
    {
        if (true === $container && file_exists($this->container)) {
            unlink($this->container);
        }

        if (true === $meta && file_exists($this->containerMeta)) {
            unlink($this->containerMeta);
        }
    }
}
