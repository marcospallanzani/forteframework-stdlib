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

namespace Forte\Stdlib\Exceptions;

/**
 * Methods to easily throw exceptions.
 *
 * @package Forte\Stdlib\Exceptions
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
trait ThrowErrorsTrait
{
    /**
     * Throw a GeneralException with the given message and parameters.
     *
     * @param string $message The exception message.
     * @param array<string> $parameters The values to replace in the error message.
     *
     * @throws GeneralException
     */
    public function throwGeneralException(
        string $message,
        string ...$parameters
    ): void
    {
        throw $this->getGeneralException($message, ...$parameters);
    }

    /**
     * Return a GeneralException with the given message and parameters.
     *
     * @param string $message The exception message.
     * @param array<string> $parameters The values to replace in the error message.
     *
     * @return GeneralException A GeneralException instance with the given message and parameters.
     */
    public function getGeneralException(
        string $message,
        string ...$parameters
    ): GeneralException
    {
        return new GeneralException(vsprintf($message, $parameters));
    }

    /**
     * Throw a MissingKeyException with the given message and parameters.
     *
     * @param string $key The missing key.
     * @param string $message The exception message.
     * @param array<string> $parameters The values to replace in the error message.
     *
     * @throws MissingKeyException
     */
    public function throwMissingKeyException(
        string $key,
        string $message,
        string ...$parameters
    ): void
    {
        throw $this->getMissingKeyException($key, $message, ...$parameters);
    }

    /**
     * Return a MissingKeyException with the given message and parameters.
     *
     * @param string $key The missing key.
     * @param string $message The exception message.
     * @param string ...$parameters The values to replace in the error message.
     *
     * @return MissingKeyException A MissingKeyException instance with the given key, message and parameters.
     */
    public function getMissingKeyException(
        string $key,
        string $message,
        string ...$parameters
    ): MissingKeyException
    {
        return new MissingKeyException($key, vsprintf($message, $parameters));
    }

    /**
     * Throw a WrongParameterException with the given message and parameters.
     *
     * @param string $message The exception message.
     * @param array<string> $parameters The values to replace in the error message.
     *
     * @throws WrongParameterException
     */
    public function throwWrongParameterException(
        string $message,
        string ...$parameters
    ): void
    {
        throw $this->getWrongParameterException($message, ...$parameters);
    }

    /**
     * Return a WrongParameterException with the given message and parameters.
     *
     * @param string $message The exception message.
     * @param string ...$parameters The values to replace in the error message.
     *
     * @return WrongParameterException A WrongParameterException instance with the given message and parameters.
     */
    public function getWrongParameterException(
        string $message,
        string ...$parameters
    ): WrongParameterException
    {
        return new WrongParameterException(vsprintf($message, $parameters));
    }
}
