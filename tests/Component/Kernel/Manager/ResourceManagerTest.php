<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use Ulrack\Kernel\Common\Manager\CodecManagerInterface;
use Ulrack\Kernel\Component\Kernel\Manager\ResourceManager;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\ResourceManager
 */
class ResourceManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::getFileSystemDriver
     * @covers ::getRootFileSystem
     * @covers ::getVarFileSystem
     */
    public function testManager(): void
    {
        $codecManager = $this->createMock(CodecManagerInterface::class);
        $tempDir = sys_get_temp_dir() . '/ulrack_kernel_test/' . bin2hex(random_bytes(5));
        mkdir($tempDir, 0777, true);

        $subject = new ResourceManager($codecManager, $tempDir);

        $subject->boot();

        $this->assertInstanceOf(
            FileSystemDriverInterface::class,
            $subject->getFileSystemDriver()
        );

        $this->assertInstanceOf(
            FileSystemInterface::class,
            $subject->getRootFileSystem()
        );

        $this->assertInstanceOf(
            FileSystemInterface::class,
            $subject->getVarFileSystem()
        );

        $this->assertNotSame(
            $subject->getVarFileSystem(),
            $subject->getRootFileSystem()
        );
    }
}
