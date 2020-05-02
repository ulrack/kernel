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
use GrizzIt\Cache\Common\CacheRegistryInterface;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use GrizzIt\Vfs\Common\FileSystemNormalizerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\CacheManager;
use Ulrack\Kernel\Common\Manager\ResourceManagerInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\CacheManager
 */
class CacheManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::getCacheRegistry
     * @covers ::getCache
     * @covers ::registerStorageToCache
     * @covers ::__destruct
     */
    public function testManager(): void
    {
        $resourceManager = $this->createMock(ResourceManagerInterface::class);
        $fileSystemDriver = $this->createMock(FileSystemDriverInterface::class);
        $cacheFileSystem = $this->createMock(FileSystemInterface::class);
        $fileSystem = $this->createMock(FileSystemInterface::class);
        $normalizer = $this->createMock(FileSystemNormalizerInterface::class);

        $resourceManager->method('getFileSystemDriver')
            ->willReturn($fileSystemDriver);

        $fileSystemDriver->method('connect')
            ->willReturnOnConsecutiveCalls(
                $cacheFileSystem,
                $fileSystem,
                $fileSystem,
                $fileSystem,
                $fileSystem
            );

        $fileSystemDriver->method('getFileSystemNormalizer')
            ->willReturn($normalizer);

        $fileSystem->expects(static::once())
            ->method('isFile')
            ->with('bar.json')
            ->willReturn(true);

        $normalizer->expects(static::once())
            ->method('normalizeFromFile')
            ->with($fileSystem, 'bar.json')
            ->willReturn(['ttl' => null, 'value' => ['bar' => 'baz']]);

        $subject = new CacheManager($resourceManager);
        $subject->boot();

        $this->assertInstanceOf(
            CacheRegistryInterface::class,
            $subject->getCacheRegistry()
        );

        $cache = $subject->getCache('foo');

        $this->assertInstanceOf(
            CacheInterface::class,
            $cache
        );

        $subject->registerStorageToCache(
            'foo',
            'bar',
            new ObjectStorage(['foo' => 'bar'])
        );

        $value = ['ttl' => null, 'value' => ['bar' => 'baz', 'foo' => 'bar']];

        $normalizer->expects(static::once())
            ->method('denormalizeToFile')
            ->with($fileSystem, 'bar.json', $value);
    }
}
