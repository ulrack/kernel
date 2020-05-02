<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Kernel\Manager;

use PHPUnit\Framework\TestCase;
use Ulrack\Kernel\Component\Kernel\Kernel;
use Ulrack\Kernel\Common\ApplicationInterface;
use Ulrack\Kernel\Common\Manager\CoreManagerInterface;
use Ulrack\Kernel\Common\Manager\ServiceManagerInterface;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Kernel\Kernel
 */
class KernelTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::run
     */
    public function testManager(): void
    {
        $coreManager = $this->createMock(CoreManagerInterface::class);
        $coreManager->expects(static::once())
            ->method('boot');

        $serviceManager = $this->createMock(ServiceManagerInterface::class);

        $coreManager->expects(static::once())
            ->method('getServiceManager')
            ->willReturn($serviceManager);

        $subject = new Kernel($coreManager);

        $application = $this->createMock(ApplicationInterface::class);

        $application->expects(static::once())
            ->method('run')
            ->with($serviceManager);

        $subject->run($application);
    }
}
