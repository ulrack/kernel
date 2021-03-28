<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Storage;

use GrizzIt\Storage\Component\ObjectStorage;

class SchemaStorage extends ObjectStorage
{
    /**
     * Sets data on a specific key within the storage.
     *
     * @param string|int $key
     * @param mixed  $data
     *
     * @return void
     */
    public function set(string | int $key, mixed $data): void
    {
        parent::set($key, json_decode(json_encode($data)));
    }
}
