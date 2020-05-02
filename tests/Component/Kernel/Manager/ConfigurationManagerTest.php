<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Cache\Common\CacheInterface;
use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\Vfs\Common\FileSystemNormalizerInterface;
use Ulrack\Kernel\Common\Manager\CacheManagerInterface;
use Ulrack\Kernel\Common\Manager\ResourceManagerInterface;
use Ulrack\Kernel\Common\InitializableKernelManagerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ConfigurationManager;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\ConfigurationManager
 */
class ConfigurationManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::loadConfigurationFromCache
     * @covers ::registerInitPoint
     * @covers ::initPoint
     * @covers ::getConfigRegistry
     */
    public function testManager(): void
    {
        $resourceManager = $this->createMock(ResourceManagerInterface::class);
        $cacheManager = $this->createMock(CacheManagerInterface::class);
        $subject = new ConfigurationManager(
            $resourceManager,
            $cacheManager
        );

        $initKernel = $this->createMock(
            InitializableKernelManagerInterface::class
        );

        $initKernel->expects(static::once())
            ->method('initialize')
            ->with($this->isInstanceOf(RegistryInterface::class));

        $subject->registerInitPoint(
            ConfigurationManager::INIT_POINT_PREPARE_CODEC,
            $initKernel
        );

        $cache = $this->createMock(CacheInterface::class);

        $cache->expects(static::once())
            ->method('exists')
            ->with('configuration')
            ->willReturn(true);

        $cache->expects(static::once())
            ->method('fetch')
            ->with('configuration')
            ->willReturn(new ObjectStorage());

        $cacheManager->expects(static::once())
            ->method('getCache')
            ->with('configuration')
            ->willReturn($cache);

        $subject->boot();

        $this->assertInstanceOf(
            RegistryInterface::class,
            $subject->getConfigRegistry()
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::prepareConfiguration
     * @covers ::preloadConfiguration
     * @covers ::registerInitPoint
     */
    public function testPrepareConfiguration(): void
    {
        $resourceManager = $this->createMock(ResourceManagerInterface::class);
        $cacheManager = $this->createMock(CacheManagerInterface::class);
        $subject = new ConfigurationManager(
            $resourceManager,
            $cacheManager
        );

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects(static::once())
            ->method('exists')
            ->with('configuration')
            ->willReturn(false);

        $cacheManager->expects(static::once())
            ->method('getCache')
            ->with('configuration')
            ->willReturn($cache);

        $driver = $this->createMock(FileSystemDriverInterface::class);
        $fileSystem = $this->createMock(FileSystemInterface::class);
        $resourceManager->expects(static::once())
            ->method('getFileSystemDriver')
            ->willReturn($driver);

        $normalizer = $this->createMock(FileSystemNormalizerInterface::class);

        $driver->expects(static::exactly(3))
            ->method('getFileSystemNormalizer')
            ->willReturn($normalizer);

        $driver->expects(static::exactly(6))
            ->method('connect')
            ->willReturn($fileSystem);

        $fileSystem->method('list')
            ->willReturn(['foo.json']);

        $fileSystem->method('isReadable')
            ->willReturn(true);

        $normalizer->method('normalizeFromFile')
            ->willReturn(['key' => 'foo', 'location' => 'configuration']);

        $subject->boot();
    }
}
