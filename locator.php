<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

use Ulrack\Kernel\Common\UlrackKernelPackage;
use GrizzIt\Configuration\Component\Configuration\PackageLocator;

PackageLocator::registerLocation(__DIR__, UlrackKernelPackage::PACKAGE_NAME);
