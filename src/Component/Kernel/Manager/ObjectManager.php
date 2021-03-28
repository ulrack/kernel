<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use GrizzIt\ObjectFactory\Common\MethodReflectorInterface;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;
use GrizzIt\ObjectFactory\Component\Reflector\MethodReflector;

class ObjectManager implements ObjectManagerInterface
{
    /**
     * Contains the analysis storage.
     *
     * @var StorageInterface
     */
    private StorageInterface $analysisStorage;

    /**
     * Contains the class analyser.
     *
     * @var ClassAnalyserInterface
     */
    private ClassAnalyserInterface $classAnalyser;

    /**
     * Contains the object factory.
     *
     * @var ObjectFactoryInterface
     */
    private ObjectFactoryInterface $objectFactory;

    /**
     * Contains the method reflector.
     *
     * @var MethodReflectorInterface
     */
    private MethodReflectorInterface $methodReflector;

    /**
     * Constructor.
     *
     * @param ObjectFactoryInterface $objectFactory
     */
    public function __construct(StorageInterface $analysisStorage)
    {
        $this->analysisStorage = $analysisStorage;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->methodReflector = new MethodReflector($this->analysisStorage);
        $this->classAnalyser = new ClassAnalyser(
            $this->analysisStorage,
            $this->methodReflector
        );
        $this->objectFactory = new ObjectFactory($this->classAnalyser);
    }

    /**
     * Retrieves the method reflector.
     *
     * @return MethodReflectorInterface
     */
    public function getMethodReflector(): MethodReflectorInterface
    {
        return $this->methodReflector;
    }

    /**
     * Retrieves the object factory.
     *
     * @return ObjectFactoryInterface
     */
    public function getObjectFactory(): ObjectFactoryInterface
    {
        return $this->objectFactory;
    }

    /**
     * Retrieves the class analyser.
     *
     * @return ClassAnalyserInterface
     */
    public function getClassAnalyser(): ClassAnalyserInterface
    {
        return $this->classAnalyser;
    }

    /**
     * Retrieves the analysis storage.
     *
     * @return StorageInterface
     */
    public function getAnalysisStorage(): StorageInterface
    {
        return $this->analysisStorage;
    }
}
