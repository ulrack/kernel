<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Common\Manager;

use GrizzIt\Vfs\Common\FileSystemInterface;
use Ulrack\Kernel\Common\KernelManagerInterface;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;

interface ResourceManagerInterface extends KernelManagerInterface
{
    /**
     * Retrieves the file system driver.
     *
     * @return FileSystemDriverInterface
     */
    public function getFileSystemDriver(): FileSystemDriverInterface;

    /**
     * Retrieves the root file system.
     *
     * @return FileSystemInterface
     */
    public function getRootFileSystem(): FileSystemInterface;

    /**
     * Retrieves the var file system.
     *
     * @return FileSystemInterface
     */
    public function getVarFileSystem(): FileSystemInterface;
}
