<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel;

use Ulrack\Kernel\Common\KernelInterface;
use Ulrack\Kernel\Common\ApplicationInterface;
use Ulrack\Kernel\Common\Manager\CoreManagerInterface;

class Kernel implements KernelInterface
{
    /**
     * Contains the core manager.
     *
     * @var CoreManagerInterface
     */
    private $coreManager;

    /**
     * Constructor.
     *
     * @param CoreManagerInterface $coreManager
     */
    public function __construct(CoreManagerInterface $coreManager)
    {
        $this->coreManager = $coreManager;
    }

    /**
     * Run an application.
     *
     * @param ApplicationInterface $application
     *
     * @return void
     */
    public function run(ApplicationInterface $application): void
    {
        $this->coreManager->boot();

        $application->run(
            $this->coreManager->getServiceManager()
        );
    }
}
