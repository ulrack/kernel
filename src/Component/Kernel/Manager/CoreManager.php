<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Storage\Component\ObjectStorage;
use Ulrack\Kernel\Component\Storage\SchemaStorage;
use Ulrack\Kernel\Common\Manager\CoreManagerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\CacheManager;
use Ulrack\Kernel\Component\Kernel\Manager\CodecManager;
use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ObjectManager;
use Ulrack\Kernel\Component\Kernel\Manager\ResourceManager;
use Ulrack\Kernel\Component\Kernel\Manager\ConfigurationManager;

class CoreManager implements CoreManagerInterface
{
    /**
     * Contains the root directory.
     *
     * @var string
     */
    private $rootDirectory;

    /**
     * Contains the service manager.
     *
     * @var ServiceManagerInterface
     */
    private $serviceManager;

    /**
     * Constructor.
     *
     * @param string $rootDirectory
     */
    public function __construct(string $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $classAnalysisStorage = new ObjectStorage();
        $servicesStorage = new ObjectStorage();
        $schemaStorage = new SchemaStorage();

        $objectManager = new ObjectManager($classAnalysisStorage);
        $objectManager->boot();

        $codecManager = new CodecManager($objectManager);
        $codecManager->boot();

        $resourceManager = new ResourceManager(
            $codecManager,
            $this->rootDirectory
        );
        $resourceManager->boot();

        $cacheManager = new CacheManager($resourceManager);
        $cacheManager->boot();
        $cacheManager->registerStorageToCache(
            'core',
            'analysis',
            $classAnalysisStorage
        );

        $cacheManager->registerStorageToCache(
            'core',
            'services',
            $servicesStorage
        );
        $cacheManager->registerStorageToCache('core', 'schema', $schemaStorage);

        $validationManager = new ValidationManager($schemaStorage);
        $validationManager->boot();

        $this->serviceManager = new ServiceManager(
            $objectManager,
            $servicesStorage,
            $validationManager
        );

        $this->serviceManager->boot();

        $configurationManager = new ConfigurationManager(
            $resourceManager,
            $cacheManager
        );

        $configurationManager->registerInitPoint(
            ConfigurationManager::INIT_POINT_CACHE_LOAD,
            $this->serviceManager
        );

        $configurationManager->registerInitPoint(
            ConfigurationManager::INIT_POINT_PREPARE_CODEC,
            $codecManager
        );

        $configurationManager->registerInitPoint(
            ConfigurationManager::INIT_POINT_POST_COMPILE,
            $validationManager
        );

        $configurationManager->registerInitPoint(
            ConfigurationManager::INIT_POINT_POST_COMPILE,
            $this->serviceManager
        );

        $configurationManager->boot();
        $this->registerServices(
            [
                'core.object.manager' => $objectManager,
                'core.codec.manager' => $codecManager,
                'core.resource.manager' => $resourceManager,
                'core.cache.manager' => $cacheManager,
                'core.service.manager' => $this->serviceManager,
                'core.configuration.manager' => $configurationManager,
                'core.object.manager' => $objectManager,
            ]
        );
    }

    /**
     * Registers services to the service manager.
     *
     * @param array $services
     *
     * @return void
     */
    private function registerServices(array $services): void
    {
        foreach ($services as $key => $manager) {
            $this->serviceManager->registerService($key, $manager);
        }
    }

    /**
     * Retrieves the service manager.
     *
     * @return ServiceManagerInterface
     */
    public function getServiceManager(): ServiceManagerInterface
    {
        return $this->serviceManager;
    }
}
