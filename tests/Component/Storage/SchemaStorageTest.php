<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Tests\Component\Storage;

use PHPUnit\Framework\TestCase;
use Ulrack\Kernel\Component\Storage\SchemaStorage;

/**
 * @coversDefaultClass \Ulrack\Kernel\Component\Storage\SchemaStorage
 */
class SchemaStorageTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::set
     */
    public function testStorage(): void
    {
        $subject = new SchemaStorage();
        $subject->set('foo', ['foo' => 'bar']);
        $this->assertEquals(
            'bar',
            $subject->get('foo')->foo
        );
    }
}
