<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use Ulrack\Kernel\Common\KernelManagerInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\Kernel\Common\InitializableKernelManagerInterface;

interface ConfigurationManagerInterface extends KernelManagerInterface
{
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
    ): void;

    /**
     * Retrieves the compiled configuration registry.
     *
     * @return RegistryInterface
     */
    public function getConfigRegistry(): RegistryInterface;
}
