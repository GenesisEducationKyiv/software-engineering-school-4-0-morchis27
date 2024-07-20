<?php

namespace App\Utils;

use Exception;
use Illuminate\Contracts\Config\Repository;

class Utilities
{
    public function __construct(
        private Repository $config
    ) {
    }

    public function getStringValueFromEnvVariable(
        string $configVariableSpace,
        string $configVariableKey
    ): string {
        $configVariable = $this->getValueFromEnvVariable($configVariableSpace, $configVariableKey);
//        dd($configVariable);
        if (!is_string($configVariable)) {
            throw new Exception("Couldn't cast config value of $configVariableKey to string");
        }

        return $configVariable;
    }

    /**
     * @throws Exception
     */
    public function getIntValueFromEnvVariable(
        string $configVariableSpace,
        string $configVariableKey
    ): int {
        $configVariable = $this->getValueFromEnvVariable($configVariableSpace, $configVariableKey);

        if (!is_int($configVariable)) {
            throw new Exception("Couldn't cast config value of $configVariableKey to int");
        }

        return $configVariable;
    }

    private function getValueFromEnvVariable(
        string $configVariableSpace,
        string $configVariableKey
    ): mixed {
        return $this->config->get($configVariableSpace . '.' . $configVariableKey);
    }
}
