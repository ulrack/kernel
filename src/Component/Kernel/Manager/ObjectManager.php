<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;

class ObjectManager implements ObjectManagerInterface
{
    /**
     * Contains the analysis storage.
     *
     * @var StorageInterface
     */
    private $analysisStorage;

    /**
     * Contains the class analyser.
     *
     * @var ClassAnalyserInterface
     */
    private $classAnalyser;

    /**
     * Contains the object factory.
     *
     * @var ObjectFactoryInterface
     */
    private $objectFactory;

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
        $this->classAnalyser = new ClassAnalyser($this->analysisStorage);
        $this->objectFactory = new ObjectFactory($this->classAnalyser);
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
