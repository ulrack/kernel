<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use Ulrack\Kernel\Component\Kernel\Manager\CoreManager;
use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Manager\CoreManager
 */
class CoreManagerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::boot
     * @covers ::registerServices
     * @covers ::getServiceManager
     */
    public function testManager(): void
    {
        $tempDir = sys_get_temp_dir() . '/ulrack_kernel_test/' . bin2hex(random_bytes(5));
        mkdir($tempDir, 0777, true);
        $subject = new CoreManager($tempDir);
        $subject->boot();
        $this->assertInstanceOf(
            ServiceManagerInterface::class,
            $subject->getServiceManager()
        );
    }
}
