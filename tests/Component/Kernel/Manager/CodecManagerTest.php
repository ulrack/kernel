<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use Ulrack\Kernel\Tests\Mocks\CodecInterface;
use GrizzIt\Codec\Common\CodecRegistryInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\Kernel\Common\Manager\ObjectManagerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\CodecManager;
use GrizzIt\Translator\Common\ArrayTranslatorInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\CodecManager
 */
class CodecManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::initialize
     * @covers ::getCodecRegistry
     * @covers ::getExtensionToMime
     * @covers ::getMimeToCodec
     */
    public function testManager(): void
    {
        $objectManager = $this->createMock(ObjectManagerInterface::class);
        $objectFactory = $this->createMock(ObjectFactoryInterface::class);
        $configRegistry = $this->createMock(RegistryInterface::class);
        $subject = new CodecManager($objectManager);
        $subject->boot();

        $objectManager->expects(static::once())
            ->method('getObjectFactory')
            ->willReturn($objectFactory);

        $objectFactory->expects(static::once())
            ->method('create')
            ->with('JsonCodecClass', [])
            ->willReturn($this->createMock(CodecInterface::class));

        $configRegistry->expects(static::exactly(3))
            ->method('get')
            ->withConsecutive(
                ['extension-to-mime'],
                ['mime-to-codec'],
                ['codec']
            )->willReturnOnConsecutiveCalls(
                [
                    ['left' => ['json'], 'right' => ['application/json']]
                ],
                [
                    ['left' => ['application/json'], 'right' => ['json']]
                ],
                [
                    [
                        'class' => 'JsonCodecClass',
                        'supportEncoding' => true,
                        'supportDecoding' => true,
                        'type' => 'json'
                    ]
                ]
            );

        $subject->initialize($configRegistry);

        $this->assertInstanceOf(
            CodecRegistryInterface::class,
            $subject->getCodecRegistry()
        );

        $this->assertInstanceOf(
            ArrayTranslatorInterface::class,
            $subject->getExtensionToMime()
        );

        $this->assertInstanceOf(
            ArrayTranslatorInterface::class,
            $subject->getMimeToCodec()
        );
    }
}
