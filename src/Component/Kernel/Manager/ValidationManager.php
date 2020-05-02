<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Kernel\Manager;

use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\Configuration\Common\RegistryInterface;
use Ulrack\JsonSchema\Factory\SchemaValidatorFactory;
use Ulrack\JsonSchema\Component\Storage\StorageManager;
use Ulrack\Kernel\Common\Manager\ValidationManagerInterface;
use Ulrack\JsonSchema\Common\SchemaValidatorFactoryInterface;

class ValidationManager implements ValidationManagerInterface
{
    /**
     * Contains the storage with all identifiable schemas.
     *
     * @var StorageInterface
     */
    private $schemaStorage;

    /**
     * Contains the validator factory.
     *
     * @var SchemaValidatorFactoryInterface
     */
    private $validatorFactory;

    /**
     * Constructor.
     *
     * @param StorageInterface $schemaStorage
     */
    public function __construct(StorageInterface $schemaStorage)
    {
        $this->schemaStorage = $schemaStorage;
    }

    /**
     * Boot the kernel manager.
     *
     * @return void
     */
    public function boot(): void
    {
        $storageManager = new StorageManager($this->schemaStorage);
        $this->validatorFactory = new SchemaValidatorFactory(
            null,
            $storageManager
        );
    }

    /**
     * Initializes the validation manager with the compiled configuration.
     *
     * @param RegistryInterface $configRegistry
     *
     * @return void
     */
    public function initialize(
        RegistryInterface $configRegistry
    ): void {
        foreach ($configRegistry->get('schema') as $schema) {
            if (isset($schema['$id'])) {
                $this->schemaStorage->set($schema['$id'], $schema);
            }
        }
    }

    /**
     * Retrieves the validator factory.
     *
     * @return SchemaValidatorFactoryInterface
     */
    public function getValidatorFactory(): SchemaValidatorFactoryInterface
    {
        return $this->validatorFactory;
    }
}
