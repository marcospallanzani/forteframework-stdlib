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
     * @param string $parameterMessage A short description of the specified parameter.
     *
     * @return bool
     *
     * @throws WrongParameterException
     */
    public function validateNonEmptyParameter($parameter, string $parameterMessage): bool
    {
        if (!is_numeric($parameter) && !is_bool($parameter) && !$parameter) {
            throw new WrongParameterException("Parameter $parameterMessage cannot be empty.");
        }

        return true;
    }

    /**
     * Check if the given parameter is contained in the passed accepted parameters list.
     *
     * @param mixed $parameter The parameter value to be validated.
     * @param array $acceptedParameters The accepted parameter values list.
     * @param string $parameterMessage A short description of the specified parameter.
     *
     * @return bool
     *
     * @throws Exceptions\WrongParameterException
     */
    public function validateParameterInAcceptedList(
        $parameter,
        array $acceptedParameters,
        string $parameterMessage
    ): bool
    {
        if (!in_array($parameter, $acceptedParameters)) {
            throw new WrongParameterException(sprintf(
                "Unsupported %s with value [%s]. Supported visibilities [%s].",
                $parameterMessage,
                $parameter,
                implode(', ', $acceptedParameters)
            ));
        }

        return true;
    }
}
