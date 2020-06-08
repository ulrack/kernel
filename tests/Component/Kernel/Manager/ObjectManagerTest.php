<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ObjectManager;
use GrizzIt\ObjectFactory\Common\MethodReflectorInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\ObjectManager
 */
class ObjectManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::getObjectFactory
     * @covers ::getClassAnalyser
     * @covers ::getAnalysisStorage
     * @covers ::getMethodReflector
     */
    public function testManager(): void
    {
        $analysisStorage = $this->createMock(StorageInterface::class);
        $subject = new ObjectManager($analysisStorage);
        $subject->boot();

        $this->assertInstanceOf(
            ObjectFactoryInterface::class,
            $subject->getObjectFactory()
        );

        $this->assertInstanceOf(
            ClassAnalyserInterface::class,
            $subject->getClassAnalyser()
        );

        $this->assertInstanceOf(
            MethodReflectorInterface::class,
            $subject->getMethodReflector()
        );

        $this->assertSame($analysisStorage, $subject->getAnalysisStorage());
    }
}
