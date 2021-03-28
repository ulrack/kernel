<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Compiler;

use PHPUnit\Framework\TestCase;
use GrizzIt\Validator\Common\ValidatorInterface;
use Ulrack\Kernel\Component\Compiler\WrappedServiceCompiler;
use Ulrack\Kernel\Component\Registry\WrappedServiceRegistry;
use GrizzIt\Services\Common\Compiler\ServiceCompilerInterface;
use GrizzIt\Services\Common\Registry\ServiceRegistryInterface;
use GrizzIt\Services\Common\Compiler\ServiceCompilerExtensionInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Compiler\WrappedServiceCompiler
 */
class WrappedServiceCompilerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::compile
     * @covers ::addValidator
     * @covers ::addExtension
     */
    public function testComponent(): void
    {
        $serviceCompiler = $this->createMock(ServiceCompilerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $extension = $this->createMock(ServiceCompilerExtensionInterface::class);
        $subject = new WrappedServiceCompiler($serviceCompiler);

        $serviceCompiler->expects(static::once())
            ->method('compile')
            ->willReturn($this->createMock(ServiceRegistryInterface::class));

        $this->assertInstanceOf(
            WrappedServiceRegistry::class,
            $subject->compile()
        );

        $serviceCompiler->expects(static::once())
            ->method('addValidator')
            ->with('foo', $validator);
        $subject->addValidator('foo', $validator);

        $serviceCompiler->expects(static::once())
            ->method('addExtension')
            ->with($extension, 0);
        $subject->addExtension($extension, 0);
    }
}
