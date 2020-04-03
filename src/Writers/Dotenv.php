<?php

namespace Forte\Stdlib\Writers;

use Forte\Stdlib\Exceptions\GeneralException;
use Laminas\Config\Writer\AbstractWriter;

/**
 * Class Dotenv.
 *
 * @package Forte\Stdlib\Writers
 */
class Dotenv extends AbstractWriter
{
    /**
     * Convert the given config array to a .env string.
     *
     * @param array $config The array to be converted to a .env string.
     *
     * @return string .env string representation of the given config array.
     *
     * @throws GeneralException
     */
    protected function processConfig(array $config)
    {
        $dotenvString = '';

        foreach ($config as $key => $data) {
            $dotenvString .= $key .  '=' .  $this->prepareValue($data) .  PHP_EOL;
        }

        return $dotenvString;
    }

    /**
     * Prepare a value to be written in a .env file.
     *
     * @param mixed $value The value to be prepared to be written in a .env file.
     *
     * @return string
     *
     * @throws GeneralException
     */
    protected function prepareValue($value)
    {
        if (is_object($value)) {
            throw new GeneralException("Objects are not accepted as a possible value in a .env file.");
        }

        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return ($value ? 'true' : 'false');
        }

        return $value;
    }
}