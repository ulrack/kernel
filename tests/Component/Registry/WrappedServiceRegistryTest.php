<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Registry;

use PHPUnit\Framework\TestCase;
use Ulrack\Kernel\Component\Registry\WrappedServiceRegistry;
use GrizzIt\Services\Common\Registry\ServiceRegistryInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Registry\WrappedServiceRegistry
 */
class WrappedServiceRegistryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setServiceRegistry
     * @covers ::getDefinitionByKey
     * @covers ::exists
     * @covers ::getKeys
     */
    public function testComponent(): void
    {
        putenv('BAZ=qux');
        $serviceRegistry = $this->createMock(ServiceRegistryInterface::class);
        $subject = new WrappedServiceRegistry(
            $this->createMock(ServiceRegistryInterface::class)
        );

        $subject->setServiceRegistry($serviceRegistry);

        $serviceRegistry->expects(static::once())
            ->method('exists')
            ->with('parameters.foo')
            ->willReturn(true);

        $serviceRegistry->expects(static::once())
            ->method('getDefinitionByKey')
            ->with('parameters.foo')
            ->willReturn('bar');

        $this->assertEquals(true, $subject->exists('parameters.foo'));
        $this->assertEquals(true, $subject->exists('parameters.BAZ'));

        $this->assertEquals(
            'bar',
            $subject->getDefinitionByKey('parameters.foo')
        );

        $this->assertEquals(
            'qux',
            $subject->getDefinitionByKey('parameters.BAZ')
        );
    }
}
