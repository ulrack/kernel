<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Vfs\Common\FileSystemInterface;
use GrizzIt\Vfs\Common\FileSystemDriverInterface;
use GrizzIt\Vfs\Component\Driver\LocalFileSystemDriver;
use Ulrack\Kernel\Common\Manager\CodecManagerInterface;
use GrizzIt\Vfs\Component\FileSystem\FileSystemNormalizer;
use Ulrack\Kernel\Common\Manager\ResourceManagerInterface;

class ResourceManager implements ResourceManagerInterface
{
    /**
     * Contains the codec manager.
     *
     * @var CodecManagerInterface
     */
    private CodecManagerInterface $codecManager;

    /**
     * Contains the file system driver for the system.
     *
     * @var FileSystemDriverInterface
     */
    private FileSystemDriverInterface $fileSystemDriver;

    /**
     * Contains the root file system.
     *
     * @var FileSystemInterface
     */
    private FileSystemInterface $rootFileSystem;

    /**
     * Contains the var file system.
     *
     * @var FileSystemInterface
     */
    private FileSystemInterface $varFileSystem;

    /**
     * Contains the root directory for the system.
     *
     * @var string
     */
    private string $systemRoot;

    /**
     * Contains the var directory for the system.
     *
     * @var string
     */
    private string $varDirectory;

    /**
     * Constructor.
     *
     * @param CodecManagerInterface $codecManager
     * @param string $systemRoot
     * @param string $varDirectory
     */
    public function __construct(
        CodecManagerInterface $codecManager,
        string $systemRoot,
        string $varDirectory = '/var'
    ) {
        $this->codecManager = $codecManager;
        $this->systemRoot = $systemRoot;
        $this->varDirectory = $varDirectory;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->fileSystemDriver = new LocalFileSystemDriver(
            new FileSystemNormalizer(
                $this->codecManager->getCodecRegistry(),
                $this->codecManager->getExtensionToMime(),
                $this->codecManager->getMimeToCodec()
            )
        );

        $this->rootFileSystem = $this->fileSystemDriver->connect(
            $this->systemRoot
        );

        if (!$this->rootFileSystem->isDirectory($this->varDirectory)) {
            $this->rootFileSystem->makeDirectory($this->varDirectory);
        }

        $this->varFileSystem = $this->fileSystemDriver->connect(
            $this->systemRoot . $this->varDirectory
        );
    }

    /**
     * Retrieves the file system driver.
     *
     * @return FileSystemDriverInterface
     */
    public function getFileSystemDriver(): FileSystemDriverInterface
    {
        return $this->fileSystemDriver;
    }

    /**
     * Retrieves the root file system.
     *
     * @return FileSystemInterface
     */
    public function getRootFileSystem(): FileSystemInterface
    {
        return $this->rootFileSystem;
    }

    /**
     * Retrieves the var file system.
     *
     * @return FileSystemInterface
     */
    public function getVarFileSystem(): FileSystemInterface
    {
        return $this->varFileSystem;
    }
}
