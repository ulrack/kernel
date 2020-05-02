<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Factory\Hook;

use PHPUnit\Framework\TestCase;
use Ulrack\Kernel\Factory\Hook\EnvironmentVariableHook;

/**
 * @coversDefaultClass \Ulrack\Kernel\Factory\Hook\EnvironmentVariableHook
 */
class EnvironmentVariableHookTest extends TestCase
{
    /**
     * @param array $source
     * @param string $service
     * @param string $expected
     *
     * @return void
     *
     * @covers ::postCreate
     *
     * @dataProvider hookProvider
     */
    public function testHook(
        string $service,
        array $expected
    ): void {
        $subject = new EnvironmentVariableHook(
            'parameters',
            [],
            [],
            []
        );

        $this->assertEquals(
            $expected,
            $subject->postCreate('foo', $service, [])
        );
    }

    /**
     * Hook provider.
     *
     * @return array
     */
    public function hookProvider(): array
    {
        putenv('FOO=bar');

        return [
            [
                '${FOO}',
                [
                    'serviceKey' => 'foo',
                    'return' => 'bar',
                    'parameters' => []
                ]
            ],
            [
                '${BAR}',
                [
                    'serviceKey' => 'foo',
                    'return' => '${BAR}',
                    'parameters' => []
                ]
            ]
        ];
    }
}
