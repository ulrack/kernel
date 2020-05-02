<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common;

interface KernelManagerInterface
{
    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void;
}
