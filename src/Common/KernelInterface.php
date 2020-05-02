<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common;

interface KernelInterface
{
    /**
     * Run an application.
     *
     * @param ApplicationInterface $application
     *
     * @return void
     */
    public function run(ApplicationInterface $application): void;
}
