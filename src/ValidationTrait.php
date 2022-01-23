<?php

declare(strict_types=1);

/*
 * This file is part of the ForteFramework Standard Library package.
 *
 * (c) Marco Spallanzani <forteframework@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Forte\Stdlib;

use Forte\Stdlib\Exceptions\WrongParameterException;

/**
 * Validation methods.
 *
 * @package Forte\Stdlib
 * @author  Marco Spallanzani <forteframework@gmail.com>
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
     * @param string $parameterName An identifier of the given object list (for log purpose only).
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
     * Check if the given parameter is contained in the given accepted parameters list.
     *
     * @param mixed $parameter The parameter value to be validated.
     * @param array $acceptedParameters The accepted parameter values list.
     * @param string $parameterName A short description of the specified parameter.
     *
     * @return bool True if the given parameter is contained in the given list of parameters; false otherwise.
     *
     * @throws Exceptions\WrongParameterException
     */
    public function validateParameterInAcceptedList(
        $parameter,
        array $acceptedParameters,
        string $parameterName
    ): bool
    {
        //TODO this function needs to be refactored.. is it really required? is it an associative array?
        if (!in_array($parameter, $acceptedParameters, true)) {
            throw new WrongParameterException(sprintf(
                'Unsupported %s with value [%s]. Supported parameters: [%s].',
                $parameterName,
                $parameter,
                implode(', ', $acceptedParameters)
            ));
        }

        return true;
    }
}
