<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\Kernel\Factory\Hook;

use Ulrack\Services\Common\Hook\AbstractServiceFactoryHook;

class EnvironmentVariableHook extends AbstractServiceFactoryHook
{
    /**
     * Hooks in after the creation of a service.
     *
     * @param string $serviceKey
     * @param mixed $return
     * @param array $parameters
     *
     * @return mixed
     */
    public function postCreate(
        string $serviceKey,
        $return,
        array $parameters = []
    ): array {
        if (preg_match('/\\$\\{([\\w]+)\\}/', $return, $matches)) {
            if (isset($matches[1])) {
                $variable = getenv($matches[1], true) ?: getenv($matches[1]);
                if ($variable !== false) {
                    $return = $variable;
                }
            }
        }

        return parent::postCreate($serviceKey, $return, $parameters);
    }
}
