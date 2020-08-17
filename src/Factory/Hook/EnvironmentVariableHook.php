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
        return parent::postCreate(
            $serviceKey,
            $this->parseEnvironmentVariables($return),
            $parameters
        );
    }

    /**
     * Find and replace environment variables.
     *
     * @param mixed $return
     *
     * @return mixed
     */
    private function parseEnvironmentVariables($return)
    {
        if (is_array($return)) {
            foreach ($return as $key => $variable) {
                $return[$key] = $this->parseEnvironmentVariables($variable);
            }
        } elseif (is_string($return)) {
            if (preg_match('/\\$\\{([\\w]+)\\}/', $return, $matches)) {
                if (isset($matches[1])) {
                    $variable = getenv($matches[1], true) ?: getenv($matches[1]);
                    if ($variable !== false) {
                        $return = $variable;
                    }
                }
            }
        }

        return $return;
    }
}
