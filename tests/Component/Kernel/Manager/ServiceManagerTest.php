<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\Services\Common\ServiceFactoryInterface;
use Ulrack\Services\Common\ServiceCompilerInterface;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use Ulrack\Services\Factory\Extension\ServicesFactory;
use Ulrack\Services\Component\Compiler\ServiceCompiler;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ServiceManager;
use Ulrack\Kernel\Common\Manager\ValidationManagerInterface;

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
     * @covers ::registerConfiguration
     * @covers ::registerService
     * @covers ::getServiceCompiler
     * @covers ::getServiceFactory
     */
    public function testManager(): void
    {
        $objectManager = $this->createMock(ObjectManagerInterface::class);
        $serviceStorage = $this->createMock(StorageInterface::class);

        $subject = new ServiceManager(
            $objectManager,
            $serviceStorage,
            $this->createMock(ValidationManagerInterface::class)
        );

        $objectFactory = $this->createMock(ObjectFactoryInterface::class);
        $objectFactory->method('create')
            ->with(ServicesFactory::class)
            ->willReturn($this->createMock(ServicesFactory::class));

        $objectManager->expects(static::exactly(2))
            ->method('getObjectFactory')
            ->willReturn($objectFactory);

        $subject->boot();
        $configRegistry = $this->createMock(RegistryInterface::class);

        $serviceStorage->expects(static::exactly(2))
            ->method('has')
            ->with(ServiceCompiler::STORAGE_COMPILED_KEY)
            ->willReturn(true);

        $serviceStorage->expects(static::exactly(2))
            ->method('get')
            ->with(ServiceCompiler::STORAGE_COMPILED_KEY)
            ->willReturnOnConsecutiveCalls(false);

        $configRegistry->expects(static::exactly(7))
            ->method('get')
            ->withConsecutive(
                ['service-compiler-extensions'],
                ['service-compiler-hooks'],
                ['parameters'],
                ['preferences'],
                ['services'],
                ['service-factory-extensions'],
                ['service-factory-hooks']
            )->willReturnOnConsecutiveCalls(
                [[
                    'schema' => ['foo' => 'bar'],
                    'key' => 'compiler-extension',
                    'class' => 'foo',
                    'sortOrder' => 1
                ]],
                [[
                    'key' => 'compiler-hook',
                    'class' => 'bar',
                    'sortOrder' => 2
                ]],
                [['parameters' => []]],
                [['preferences' => []]],
                [['services' => []]],
                [[
                    'key' => 'services',
                    'class' => ServicesFactory::class,
                    'sortOrder' => 1
                ]],
                [[
                    'key' => 'factory-hook',
                    'class' => 'qux',
                    'sortOrder' => 2
                ]]
            );

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
