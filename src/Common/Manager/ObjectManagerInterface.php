<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use GrizzIt\Storage\Common\StorageInterface;
use Ulrack\Kernel\Common\KernelManagerInterface;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use GrizzIt\ObjectFactory\Common\MethodReflectorInterface;

interface ObjectManagerInterface extends KernelManagerInterface
{
    /**
     * Retrieves the method reflector.
     *
     * @return MethodReflectorInterface
     */
    public function getMethodReflector(): MethodReflectorInterface;

    /**
     * Retrieves the object factory.
     *
     * @return ObjectFactoryInterface
     */
    public function getObjectFactory(): ObjectFactoryInterface;

    /**
     * Retrieves the class analyser.
     *
     * @return ClassAnalyserInterface
     */
    public function getClassAnalyser(): ClassAnalyserInterface;

    /**
     * Retrieves the analysis storage.
     *
     * @return StorageInterface
     */
    public function getAnalysisStorage(): StorageInterface;
}
