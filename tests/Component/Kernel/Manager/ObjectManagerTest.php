<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Common\StorageInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ObjectManager;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;

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

        $this->assertSame($analysisStorage, $subject->getAnalysisStorage());
    }
}
