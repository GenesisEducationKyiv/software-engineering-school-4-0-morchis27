<?php

namespace App\Utils;

use Exception;

class Utilities
{
    /**
     * @throws Exception
     */
    public static function getStringValueFromEnvVariable(
        string $configVariableSpace,
        string $configVariableKey
    ): string {
        $configVariable = self::getValueFromEnvVariable($configVariableSpace, $configVariableKey);

        if (!is_string($configVariable)) {
            throw new Exception("Couldn't cast config value of $configVariableKey to string");
        }

        return $configVariable;
    }

    /**
     * @throws Exception
     */
    public static function getIntValueFromEnvVariable(
        string $configVariableSpace,
        string $configVariableKey
    ): int {
        $configVariable = self::getValueFromEnvVariable($configVariableSpace, $configVariableKey);

        if (!is_int($configVariable)) {
            throw new Exception("Couldn't cast config value of $configVariableKey to int");
        }

        return $configVariable;
    }

    private static function getValueFromEnvVariable(
        string $configVariableSpace,
        string $configVariableKey
    ): mixed {
        return config($configVariableSpace . '.' . $configVariableKey);
    }
}
