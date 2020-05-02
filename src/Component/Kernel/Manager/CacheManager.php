<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Cache\Common\CacheInterface;
use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Cache\Common\CacheRegistryInterface;
use GrizzIt\Cache\Component\Cache\FileSystemCache;
use GrizzIt\Cache\Component\Registry\CacheRegistry;
use Ulrack\Kernel\Common\Manager\CacheManagerInterface;
use Ulrack\Kernel\Common\Manager\ResourceManagerInterface;

class CacheManager implements CacheManagerInterface
{
    /**
     * Contains the directory within the var directory for the cache.
     */
    public const CACHE_DIRECTORY = '/cache';

    /**
     * Contains the resource manager.
     *
     * @var ResourceManagerInterface
     */
    private $resourceManager;

    /**
     * Contains the cache registry.
     *
     * @var CacheRegistryInterface
     */
    private $cacheRegistry;

    /**
     * Contains the cache file system.
     *
     * @var FileSystemInterface
     */
    private $cacheFileSystem;

    /**
     * Contains the active caches.
     *
     * @var string[]
     */
    private $activeCaches = [];

    /**
     * Contains the registered caches.
     *
     * @var StorageInterface[][]
     */
    private $registeredCaches = [];

    /**
     * Constructor.
     *
     * @param ResourceManagerInterface $resourceManager
     */
    public function __construct(ResourceManagerInterface $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->cacheRegistry = new CacheRegistry();
        $varFileSystem = $this->resourceManager->getVarFileSystem();
        if (!$varFileSystem->isDirectory(self::CACHE_DIRECTORY)) {
            $varFileSystem->makeDirectory(self::CACHE_DIRECTORY);
        }

        $this->cacheFileSystem = $this->resourceManager->getFileSystemDriver()
            ->connect(
                $varFileSystem->realpath(self::CACHE_DIRECTORY)
            );
    }

    /**
     * Retrieves the cache registry.
     *
     * @return CacheRegistryInterface
     */
    public function getCacheRegistry(): CacheRegistryInterface
    {
        return $this->cacheRegistry;
    }

    /**
     * Retrieves a cache, if it does not exist, create it.
     *
     * @param string $key
     *
     * @return CacheInterface
     */
    public function getCache(string $key): CacheInterface
    {
        if (!in_array($key, $this->activeCaches)) {
            if (!$this->cacheFileSystem->isDirectory($key)) {
                $this->cacheFileSystem->makeDirectory($key);
            }

            $driver = $this->resourceManager->getFileSystemDriver();

            $fileSystem = $driver->connect(
                $this->cacheFileSystem->realpath($key)
            );

            $cache = new FileSystemCache(
                $fileSystem,
                $driver->getFileSystemNormalizer()
            );

            $this->cacheRegistry->registerCache($key, $cache);
        }

        return $this->cacheRegistry->retrieveCache($key);
    }

    /**
     * Connects a storage to a cache.
     *
     * @param string $cacheGroup
     * @param string $cacheKey
     * @param StorageInterface $storage
     *
     * @return void
     */
    public function registerStorageToCache(
        string $cacheGroup,
        string $cacheKey,
        StorageInterface $storage
    ): void {
        $cache = $this->getCache($cacheGroup);
        if ($cache->exists($cacheKey)) {
            foreach (
                iterator_to_array(
                    $cache->fetch($cacheKey)
                ) as $key => $value
            ) {
                $storage->set($key, $value);
            }
        }

        $this->registeredCaches[$cacheGroup][$cacheKey] = $storage;
    }

    /**
     * Destructor.
     *
     * Writes all caches.
     */
    public function __destruct()
    {
        foreach ($this->registeredCaches as $cacheGroup => $caches) {
            $cache = $this->getCache($cacheGroup);
            $cache->enableBuffer();
            foreach ($caches as $cacheKey => $storage) {
                $cache->store(
                    $cacheKey,
                    $storage
                );
            }

            $cache->commit();
        }
    }
}
