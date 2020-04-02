<?php

namespace Forte\Stdlib;

use Forte\Stdlib\Exceptions\WrongParameterException;

/**
 * Trait ValidationTrait.
 *
 * @package Forte\Stdlib
 */
trait ValidationTrait
{
    /**
     * Check if the given parameter is not empty.
     *
     * @param mixed $parameter The parameter value to be validated.
     * @param string $parameterName A short description of the specified parameter.
     *
     * @return bool True if the given parameter is not empty (0, false are considered as possible values).
     *
     * @throws WrongParameterException
     */
    public function validateNonEmptyParameter($parameter, string $parameterName): bool
    {
        if (!is_numeric($parameter) && !is_bool($parameter) && !$parameter) {
            throw new WrongParameterException("Parameter $parameterName cannot be empty.");
        }

        return true;
    }

    /**
     * Check if the given list only has string items.
     *
     * @param array $list The list to be checked.
     * @param string $parameterName The list name (for error message).
     *
     * @return bool True if the given list only has string values.
     *
     * @throws WrongParameterException If the given list has non-string values.
     */
    public function validateStringList(array $list, string $parameterName): bool
    {
        foreach ($list as $item) {
            if (!is_string($item)) {
                throw new WrongParameterException("$parameterName list should contain only string values.");
            }
        }

        return true;
    }

    /**
     * Check if the given list only has class instances of the given type.
     *
     * @param array $list The list to be checked.
     * @param string $expectedClass The expected item class name.
     * @param string $parameterName The list name (for error message).
     *
     * @return bool True if the given list only has class instances of the given type.
     *
     * @throws WrongParameterException If the given list has items which are not of the given type.
     */
    public function validateObjectList(array $list, string $expectedClass, string $parameterName): bool
    {
        foreach ($list as $item) {
            if (!is_a($item, $expectedClass)) {
                throw new WrongParameterException("$parameterName list should contain only $expectedClass instances.");
            }
        }

        return true;
    }

    /**
     * Check if the given parameter is contained in the passed accepted parameters list.
     *
     * @param mixed $parameter The parameter value to be validated.
     * @param array $acceptedParameters The accepted parameter values list.
     * @param string $parameterName A short description of the specified parameter.
     *
     * @return bool
     *
     * @throws Exceptions\WrongParameterException
     */
    public function validateParameterInAcceptedList(
        $parameter,
        array $acceptedParameters,
        string $parameterName
    ): bool
    {
        if (!in_array($parameter, $acceptedParameters)) {
            throw new WrongParameterException(sprintf(
                "Unsupported %s with value [%s]. Supported visibilities [%s].",
                $parameterName,
                $parameter,
                implode(', ', $acceptedParameters)
            ));
        }

        return true;
    }
}
