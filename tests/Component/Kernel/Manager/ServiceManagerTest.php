<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use GrizzIt\Services\Component\Compiler\ServiceCompiler;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ServiceManager;
use GrizzIt\Services\Common\Factory\ServiceFactoryInterface;
use Ulrack\Kernel\Common\Manager\ValidationManagerInterface;
use GrizzIt\Services\Common\Compiler\ServiceCompilerInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\ServiceManager
 */
class ServiceManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::initialize
     * @covers ::registerService
     * @covers ::getServiceCompiler
     * @covers ::getServiceFactory
     */
    public function testManager(): void
    {
        $objectManager = $this->createMock(ObjectManagerInterface::class);
        $serviceStorage = $this->createMock(StorageInterface::class);
        $objectFactory = $this->createMock(ObjectFactoryInterface::class);
        $subject = new ServiceManager(
            $objectManager,
            $serviceStorage,
            $this->createMock(ValidationManagerInterface::class)
        );

        $subject->boot();
        $objectManager->expects(static::once())
            ->method('getObjectFactory')
            ->willReturn($objectFactory);

        $configRegistry = $this->createMock(RegistryInterface::class);

        $serviceStorage->expects(static::exactly(3))
            ->method('has')
            ->with(ServiceCompiler::STORAGE_COMPILED_KEY)
            ->willReturn(true);

        $serviceStorage->expects(static::exactly(3))
            ->method('get')
            ->with(ServiceCompiler::STORAGE_COMPILED_KEY)
            ->willReturnOnConsecutiveCalls(false);

        $configRegistry->expects(static::exactly(2))
            ->method('get')
            ->with('services')
            ->willReturn([
                [
                    'triggers' => [
                        'core.service.compilers' => [],
                        'core.service.validators' => [],
                        'core.service.factories' => [],
                        'core.service.hooks' => []
                    ]
                ]
            ]);

        $subject->initialize($configRegistry);

        $subject->registerService('foo', []);
        $this->assertInstanceOf(
            ServiceCompilerInterface::class,
            $subject->getServiceCompiler()
        );

        $this->assertInstanceOf(
            ServiceFactoryInterface::class,
            $subject->getServiceFactory()
        );
    }
}
