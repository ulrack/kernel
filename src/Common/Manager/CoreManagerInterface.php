<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use Ulrack\Kernel\Common\KernelManagerInterface;
use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;

interface CoreManagerInterface extends KernelManagerInterface
{
    /**
     * Retrieves the service manager.
     *
     * @return ServiceManagerInterface
     */
    public function getServiceManager(): ServiceManagerInterface;
}
