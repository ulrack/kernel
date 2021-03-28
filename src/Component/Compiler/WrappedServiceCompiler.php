<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Compiler;

use GrizzIt\Validator\Common\ValidatorInterface;
use Ulrack\Kernel\Component\Registry\WrappedServiceRegistry;
use GrizzIt\Services\Common\Compiler\ServiceCompilerInterface;
use GrizzIt\Services\Common\Registry\ServiceRegistryInterface;
use GrizzIt\Services\Common\Compiler\ServiceCompilerExtensionInterface;

class WrappedServiceCompiler implements ServiceCompilerInterface
{
    /**
     * Contains the original service compiler.
     *
     * @var ServiceCompilerInterface
     */
    private ServiceCompilerInterface $serviceCompiler;

    /**
     * Contains the compiled service registry.
     *
     * @var ServiceRegistryInterface|null
     */
    private ?ServiceRegistryInterface $serviceRegistry = null;

    /**
     * Constructor.
     *
     * @param ServiceCompilerInterface $serviceCompiler
     */
    public function __construct(ServiceCompilerInterface $serviceCompiler)
    {
        $this->serviceCompiler = $serviceCompiler;
    }

    /**
     * Compiles the services and returns the compiled services.
     *
     * @return ServiceRegistryInterface
     */
    public function compile(): ServiceRegistryInterface
    {
        if ($this->serviceRegistry === null) {
            $this->serviceRegistry = new WrappedServiceRegistry(
                $this->serviceCompiler->compile()
            );
        }

        return $this->serviceRegistry;
    }

    /**
     * Adds a validator for a service key.
     *
     * @param string $key
     * @param ValidatorInterface $validator
     *
     * @return void
     */
    public function addValidator(
        string $key,
        ValidatorInterface $validator
    ): void {
        $this->serviceCompiler->addValidator($key, $validator);
    }

    /**
     * Adds an extension to the service compiler.
     *
     * @param ServiceCompilerExtensionInterface $extension
     * @param int $sortOrder
     *
     * @return void
     */
    public function addExtension(
        ServiceCompilerExtensionInterface $extension,
        int $sortOrder
    ): void {
        $this->serviceCompiler->addExtension($extension, $sortOrder);
    }
}
