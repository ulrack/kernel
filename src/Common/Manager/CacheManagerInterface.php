<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use GrizzIt\Cache\Common\CacheInterface;
use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Cache\Common\CacheRegistryInterface;
use Ulrack\Kernel\Common\KernelManagerInterface;

interface CacheManagerInterface extends KernelManagerInterface
{
    /**
     * Retrieves the cache file system.
     *
     * @return FileSystemInterface
     */
    public function getCacheFileSystem(): FileSystemInterface;

    /**
     * Retrieves the cache registry.
     *
     * @return CacheRegistryInterface
     */
    public function getCacheRegistry(): CacheRegistryInterface;

    /**
     * Retrieves a cache, if it does not exist, create it.
     *
     * @param string $key
     *
     * @return CacheInterface
     */
    public function getCache(string $key): CacheInterface;

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
    ): void;

    /**
     * Reset the registered caches.
     *
     * @return void
     */
    public function resetRegisteredCaches(): void;
}
