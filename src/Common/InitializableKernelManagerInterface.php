<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common;

use GrizzIt\Configuration\Common\RegistryInterface;

interface InitializableKernelManagerInterface extends KernelManagerInterface
{
    /**
     * Initializes the manager with the config registry.
     *
     * @param RegistryInterface $configRegistry
     *
     * @return void
     */
    public function initialize(RegistryInterface $configRegistry): void;
}
