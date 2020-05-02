<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use Ulrack\Kernel\Common\InitializableKernelManagerInterface;
use Ulrack\JsonSchema\Common\SchemaValidatorFactoryInterface;

interface ValidationManagerInterface extends InitializableKernelManagerInterface
{
    /**
     * Retrieves the validator factory.
     *
     * @return SchemaValidatorFactoryInterface
     */
    public function getValidatorFactory(): SchemaValidatorFactoryInterface;
}
