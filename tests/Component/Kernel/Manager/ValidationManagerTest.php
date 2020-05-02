<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ValidationManager;
use Ulrack\JsonSchema\Common\SchemaValidatorFactoryInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\ValidationManager
 */
class ValidationManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::initialize
     * @covers ::getValidatorFactory
     */
    public function testManager(): void
    {
        $schemaStorage = $this->createMock(StorageInterface::class);
        $subject = new ValidationManager($schemaStorage);

        $subject->boot();

        $registry = $this->createMock(RegistryInterface::class);

        $schema = ['$id' => 'test.schema.json'];

        $schemaStorage->expects(static::once())
            ->method('set')
            ->with('test.schema.json', $schema);

        $registry->expects(static::once())
            ->method('get')
            ->with('schema')
            ->willReturn([$schema]);

        $subject->initialize($registry);

        $this->assertInstanceOf(
            SchemaValidatorFactoryInterface::class,
            $subject->getValidatorFactory()
        );
    }
}
