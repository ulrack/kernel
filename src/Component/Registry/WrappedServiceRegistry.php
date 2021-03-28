<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Component\Registry;

use GrizzIt\Services\Common\Registry\ServiceRegistryInterface;

class WrappedServiceRegistry implements ServiceRegistryInterface
{
    /**
     * Contains the service registry.
     *
     * @var ServiceRegistryInterface
     */
    private ServiceRegistryInterface $serviceRegistry;

    /**
     * Constructor.
     *
     * @param ServiceRegistryInterface $serviceRegistry
     */
    public function __construct(ServiceRegistryInterface $serviceRegistry)
    {
        $this->serviceRegistry = $serviceRegistry;
    }

    /**
     * Overwrites the current service registry.
     *
     * @param ServiceRegistryInterface $serviceRegistry
     *
     * @return void
     */
    public function setServiceRegistry(
        ServiceRegistryInterface $serviceRegistry
    ): void {
        $this->serviceRegistry = $serviceRegistry;
    }

    /**
     * Retrieves the definition by a key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getDefinitionByKey(string $key): mixed
    {
        [$serviceKey, $localKey] = $this->getKeys($key);
        if ($serviceKey === 'parameters') {
            $variable = getenv($localKey, true) ?: getenv($localKey);
            if ($variable !== false) {
                return $variable;
            }
        }

        return $this->serviceRegistry->getDefinitionByKey($key);
    }

    /**
     * Checks whether a definition exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        [$serviceKey, $localKey] = $this->getKeys($key);
        if ($serviceKey === 'parameters') {
            $variable = getenv($localKey, true) ?: getenv($localKey);
            if ($variable !== false) {
                return true;
            }
        }

        return $this->serviceRegistry->exists($key);
    }

     /**
     * Retrieves the service and local key based on the full service key.
     *
     * @param string $key
     *
     * @return array
     */
    private function getKeys(string $key): array
    {
        $firstDot = strpos($key, '.');
        $serviceKey = $firstDot !== false ? substr($key, 0, $firstDot) : $key;
        $localKey = substr($key, $firstDot + 1);

        return [$serviceKey, $localKey];
    }
}
