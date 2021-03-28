<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use GrizzIt\Services\Common\Factory\ServiceFactoryInterface;
use Ulrack\Kernel\Common\InitializableKernelManagerInterface;
use GrizzIt\Services\Common\Compiler\ServiceCompilerInterface;

interface ServiceManagerInterface extends InitializableKernelManagerInterface
{
    /**
     * Registers a service in the service factory.
     *
     * @param string $key
     * @param mixed $service
     *
     * @return void
     */
    public function registerService(string $key, mixed $service): void;

    /**
     * Retrieves the service compiler.
     *
     * @return ServiceCompilerInterface
     */
    public function getServiceCompiler(): ServiceCompilerInterface;

    /**
     * Retrieves the service factory.
     *
     * @return ServiceFactoryInterface
     */
    public function getServiceFactory(): ServiceFactoryInterface;
}
