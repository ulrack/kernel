<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use Ulrack\Services\Factory\ServiceFactory;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\Services\Common\ServiceFactoryInterface;
use Ulrack\Services\Common\ServiceCompilerInterface;
use Ulrack\Services\Common\ServiceRegistryInterface;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use Ulrack\Services\Factory\Extension\ServicesFactory;
use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;
use Ulrack\Services\Component\Compiler\ServiceCompiler;
use Ulrack\Services\Component\Registry\ServiceRegistry;
use Ulrack\Kernel\Common\Manager\ValidationManagerInterface;

class ServiceManager implements ServiceManagerInterface
{
    /**
     * Contains the service compiler.
     *
     * @var ServiceCompilerInterface
     */
    private $serviceCompiler;

    /**
     * Contains the service factory.
     *
     * @var ServiceFactoryInterface
     */
    private $serviceFactory;

    /**
     * Contains the object manager.
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Contains the services storage.
     *
     * @var StorageInterface
     */
    private $serviceStorage;

    /**
     * Contains the validation manager.
     *
     * @var ValidationManagerInterface
     */
    private $validationManager;

    /**
     * Contains the service registry.
     *
     * @var ServiceRegistryInterface
     */
    private $serviceRegistry;

    /**
     * Constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param StorageInterface $serviceStorage
     * @param ValidationManagerInterface $validationManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        StorageInterface $serviceStorage,
        ValidationManagerInterface $validationManager
    ) {
        $this->objectManager = $objectManager;
        $this->serviceStorage = $serviceStorage;
        $this->validationManager = $validationManager;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->serviceRegistry = new ServiceRegistry();
        $this->serviceCompiler = new ServiceCompiler(
            $this->serviceRegistry,
            $this->serviceStorage,
            $this->objectManager->getObjectFactory()
        );
    }

    /**
     * Initializes the service manager with the compiled configuration.
     *
     * @param RegistryInterface $configRegistry
     *
     * @return void
     */
    public function initialize(
        RegistryInterface $configRegistry
    ): void {
        if (
            !$this->serviceStorage->has(ServiceCompiler::STORAGE_COMPILED_KEY)
            || $this->serviceStorage->get(
                ServiceCompiler::STORAGE_COMPILED_KEY
            ) === false
        ) {
            $validatorFactory = $this->validationManager->getValidatorFactory();

            foreach ($configRegistry->get('service-compiler-extensions') as $extension) {
                $extensionValidator = $validatorFactory->create(
                    json_decode(json_encode($extension['schema']))
                );

                $this->serviceCompiler->addExtension(
                    $extension['key'],
                    $extension['class'],
                    $extension['sortOrder'],
                    $extensionValidator,
                    $extension['parameters'] ?? []
                );
            }

            foreach ($configRegistry->get('service-compiler-hooks') as $hook) {
                $this->serviceCompiler->addHook(
                    $hook['key'],
                    $hook['class'],
                    $hook['sortOrder'],
                    $hook['parameters'] ?? []
                );
            }

            $this->registerConfiguration(
                'parameters',
                $configRegistry->get('parameters')
            );

            $this->registerConfiguration(
                'preferences',
                $configRegistry->get('preferences')
            );

            $this->registerConfiguration(
                'services',
                $configRegistry->get('services')
            );
        }

        $this->serviceFactory = new ServiceFactory(
            $this->serviceCompiler,
            $this->objectManager->getObjectFactory(),
            $this->objectManager->getClassAnalyser()
        );

        foreach ($configRegistry->get('service-factory-extensions') as $extension) {
            $this->serviceFactory->addExtension(
                $extension['key'],
                $extension['class'],
                $extension['parameters'] ?? []
            );
        }

        foreach ($configRegistry->get('service-factory-hooks') as $hook) {
            $this->serviceFactory->addHook(
                $hook['key'],
                $hook['class'],
                $hook['sortOrder'],
                $hook['parameters'] ?? []
            );
        }
    }

    /**
     * Registers the configuration to the service registry.
     *
     * @param string $scope
     * @param array $definitions
     *
     * @return void
     */
    private function registerConfiguration(
        string $scope,
        array $definitions
    ): void {
        if (count($definitions) > 0) {
            foreach (array_merge(...$definitions) as $key => $definition) {
                $this->serviceRegistry->add($scope, $key, $definition);
            }
        }
    }

    /**
     * Registers a service in the service factory.
     *
     * @param string $key
     * @param mixed $service
     *
     * @return void
     */
    public function registerService(string $key, $service): void
    {
        /** @var ServicesFactory $serviceFactory */
        $servicesFactory = $this->serviceFactory->getExtension('services');
        $servicesFactory->registerObject(
            sprintf('services.%s', $key),
            $service
        );
    }

    /**
     * Retrieves the service compiler.
     *
     * @return ServiceCompilerInterface
     */
    public function getServiceCompiler(): ServiceCompilerInterface
    {
        return $this->serviceCompiler;
    }

    /**
     * Retrieves the service factory.
     *
     * @return ServiceFactoryInterface
     */
    public function getServiceFactory(): ServiceFactoryInterface
    {
        return $this->serviceFactory;
    }
}
