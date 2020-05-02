<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Configuration\Dao\Locator;
use GrizzIt\Cache\Common\CacheInterface;
use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\Configuration\Common\CompilerInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\Kernel\Common\Manager\CacheManagerInterface;
use GrizzIt\Configuration\Component\Registry\Registry;
use Ulrack\Kernel\Common\Manager\ResourceManagerInterface;
use Ulrack\Kernel\Common\InitializableKernelManagerInterface;
use Ulrack\Kernel\Common\Manager\ConfigurationManagerInterface;
use GrizzIt\Configuration\Component\Compiler\ConfigurationCompiler;

class ConfigurationManager implements ConfigurationManagerInterface
{
    /**
     * The init point which is triggered after the cache load.
     */
    public const INIT_POINT_CACHE_LOAD = 'cache_load';

    /**
     * The init point which is triggered to prepare a codec manager.
     */
    public const INIT_POINT_PREPARE_CODEC = 'prepare_codec';

    /**
     * The init point which is triggered after compilation.
     */
    public const INIT_POINT_POST_COMPILE = 'post_compile';

    /**
     * Contains the resource manager for the kernel.
     *
     * @var ResourceManagerInterface
     */
    private $resourceManager;

    /**
     * Contains the configuration compiler.
     *
     * @var CompilerInterface
     */
    private $configurationCompiler;

    /**
     * Contains the finalized configuration registry.
     *
     * @var RegistryInterface
     */
    private $configRegistry;

    /**
     * Contains the cache manager.
     *
     * @var CacheManagerInterface
     */
    private $cacheManager;

    /**
     * Contains the managers registered to initialization points.
     *
     * @var InitializableKernelManagerInterface[][]
     */
    private $initPoints;

    /**
     * Constructor.
     *
     * @param ResourceManagerInterface $resourceManager
     * @param CacheManagerInterface $cacheManager
     */
    public function __construct(
        ResourceManagerInterface $resourceManager,
        CacheManagerInterface $cacheManager
    ) {
        $this->resourceManager = $resourceManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $cache = $this->cacheManager->getCache('configuration');
        $this->configRegistry = (!$cache->exists('configuration')
            ? $this->prepareConfiguration($cache)
            : $this->loadConfigurationFromCache($cache));
    }

    /**
     * Loads the configuration from the cache.
     *
     * @param CacheInterface $cache
     *
     * @return RegistryInterface
     */
    private function loadConfigurationFromCache(
        CacheInterface $cache
    ): RegistryInterface {
        $configRegistry = new Registry();
        $configRegistry->import(
            iterator_to_array($cache->fetch('configuration'))
        );

        $this->initPoint(self::INIT_POINT_PREPARE_CODEC, $configRegistry);
        $this->initPoint(self::INIT_POINT_CACHE_LOAD, $configRegistry);

        return $configRegistry;
    }

    /**
     * Prepares the configuration.
     *
     * @param CacheInterface $cache
     *
     * @return RegistryInterface
     */
    private function prepareConfiguration(
        CacheInterface $cache
    ): RegistryInterface {
        $this->configurationCompiler = new ConfigurationCompiler(
            $this->resourceManager->getFileSystemDriver()
        );

        $this->preloadConfiguration(
            [
                'extension-to-mime.configuration.json',
                'mime-to-codec.configuration.json',
                'codec.configuration.json'
            ]
        );

        $configRegistry = $this->configurationCompiler->compile();
        $this->initPoint(self::INIT_POINT_PREPARE_CODEC, $configRegistry);
        $this->preloadConfiguration(['configuration.configuration.json']);
        $configRegistry = $this->configurationCompiler->compile();
        foreach ($configRegistry->get('configuration') as $config) {
            $this->configurationCompiler->addLocator(
                new Locator(
                    $config['key'],
                    $config['location']
                )
            );
        }

        $configRegistry = $this->configurationCompiler->compile();
        $cache->store(
            'configuration',
            new ObjectStorage($configRegistry->toArray())
        );

        $this->initPoint(self::INIT_POINT_POST_COMPILE, $configRegistry);

        return $configRegistry;
    }

    /**
     * Adds a manager to an initialization point.
     *
     * @param string $point
     * @param InitializableKernelManagerInterface $manager
     *
     * @return void
     */
    public function registerInitPoint(
        string $point,
        InitializableKernelManagerInterface $manager
    ): void {
        $this->initPoints[$point][] = $manager;
    }

    /**
     * Initializes the configuration on a point.
     *
     * @param string $point
     * @param Registry $configRegistry
     *
     * @return void
     */
    private function initPoint(string $point, Registry $configRegistry): void
    {
        if (isset($this->initPoints[$point])) {
            foreach ($this->initPoints[$point] as $manager) {
                $manager->initialize($configRegistry);
            }
        }
    }

    /**
     * Retrieves the compiled configuration registry.
     *
     * @return RegistryInterface
     */
    public function getConfigRegistry(): RegistryInterface
    {
        return $this->configRegistry;
    }

    /**
     * Preload required configuration for the kernel.
     *
     * @param array $configuration
     *
     * @return void
     */
    private function preloadConfiguration(array $configuration): void
    {
        $basePath = __DIR__ . '/../../../../configuration/configuration/';
        $preload = [];

        foreach ($configuration as $config) {
            $preload = json_decode(
                file_get_contents($basePath . $config)
            );

            $this->configurationCompiler->addLocator(
                new Locator(
                    $preload->key,
                    $preload->location
                )
            );
        }
    }
}
