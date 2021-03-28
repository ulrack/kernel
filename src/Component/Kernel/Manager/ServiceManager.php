<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Services\Factory\ServiceFactory;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\Services\Factory\Hook\TriggerFactoryHook;
use GrizzIt\Services\Component\Compiler\ServiceCompiler;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;
use GrizzIt\Services\Common\Factory\ServiceFactoryInterface;
use Ulrack\Kernel\Common\Manager\ValidationManagerInterface;
use Ulrack\Kernel\Component\Compiler\WrappedServiceCompiler;
use GrizzIt\Services\Common\Compiler\ServiceCompilerInterface;
use GrizzIt\Services\Factory\Extension\ServiceFactoryExtension;
use GrizzIt\Services\Factory\Extension\TriggerFactoryExtension;
use GrizzIt\Services\Factory\Extension\ParameterFactoryExtension;
use GrizzIt\Services\Factory\Extension\InvocationFactoryExtension;
use GrizzIt\Services\Component\Compiler\Extension\ServiceCompilerExtension;
use GrizzIt\Services\Component\Compiler\Extension\TriggerCompilerExtension;

class ServiceManager implements ServiceManagerInterface
{
    /**
     * Contains the service compiler.
     *
     * @var ServiceCompilerInterface
     */
    private ServiceCompilerInterface $serviceCompiler;

    /**
     * Contains the service factory.
     *
     * @var ServiceFactoryInterface
     */
    private ServiceFactoryInterface $serviceFactory;

    /**
     * Contains the services storage.
     *
     * @var StorageInterface
     */
    private StorageInterface $serviceStorage;

    /**
     * Contains the service registry.
     *
     * @var ServiceRegistryInterface
     */
    private ServiceRegistryInterface $serviceRegistry;

    /**
     * Contains the validation manager.
     *
     * @var ValidationManagerInterface
     */
    private ValidationManagerInterface $validationManager;

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
        return;
    }

    /**
     * Initializes the service manager with the compiled configuration.
     *
     * @param RegistryInterface $configRegistry
     *
     * @return void
     */
    public function initialize(RegistryInterface $configRegistry): void
    {
        $triggerRecompile = !$this->serviceStorage->has(
            ServiceCompiler::STORAGE_COMPILED_KEY
        ) || $this->serviceStorage->get(
            ServiceCompiler::STORAGE_COMPILED_KEY
        ) === false;

        $serviceCompiler = new ServiceCompiler(
            $configRegistry,
            $this->serviceStorage
        );

        $this->serviceCompiler = new WrappedServiceCompiler($serviceCompiler);

        if ($triggerRecompile) {
            $serviceExtension = new ServiceCompilerExtension();
            $triggerExtension = new TriggerCompilerExtension();
            $this->serviceCompiler->addExtension($serviceExtension, 0);
            $this->serviceCompiler->addExtension($triggerExtension, 0);
        }

        $this->serviceFactory = new ServiceFactory($this->serviceCompiler);
        $this->serviceFactory->addExtension(
            'parameters',
            new ParameterFactoryExtension()
        );

        $this->serviceFactory->addExtension(
            'services',
            new ServiceFactoryExtension(
                $this->objectManager->getObjectFactory()
            )
        );

        $this->serviceFactory->addExtension(
            'invocations',
            new InvocationFactoryExtension(
                $this->objectManager->getMethodReflector()
            )
        );

        $this->serviceFactory->addExtension(
            'triggers',
            new TriggerFactoryExtension()
        );

        $this->serviceFactory->addHook(
            'global',
            new TriggerFactoryHook(
                $this->serviceFactory->create('internal.service.registry')
            ),
            0
        );

        $this->serviceFactory->addInternalService(
            'core.validation.manager',
            $this->validationManager
        );

        $this->serviceFactory->addInternalService(
            'core.service.compiler',
            $this->serviceCompiler
        );

        $this->serviceFactory->addInternalService(
            'core.service.factory',
            $this->serviceFactory
        );

        if ($triggerRecompile) {
            $this->serviceFactory->create('triggers.core.service.compilers');
            $this->serviceFactory->create('triggers.core.service.validators');
            $this->serviceStorage->set(
                ServiceCompiler::STORAGE_COMPILED_KEY,
                false
            );

            $this->serviceCompiler->compile()->setServiceRegistry(
                $serviceCompiler->compile()
            );
        }

        $this->serviceFactory->create('triggers.core.service.factories');
        $this->serviceFactory->create('triggers.core.service.hooks');
    }

    /**
     * Registers a service in the service factory.
     *
     * @param string $key
     * @param mixed $service
     *
     * @return void
     */
    public function registerService(string $key, mixed $service): void
    {
        $this->serviceFactory->addInternalService($key, $service);
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
