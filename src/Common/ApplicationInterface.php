<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common;

use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;

interface ApplicationInterface
{
    /**
     * Runs the application.
     *
     * @param ServiceManagerInterface $serviceManager
     *
     * @return void
     */
    public function run(ServiceManagerInterface $serviceManager): void;
}
