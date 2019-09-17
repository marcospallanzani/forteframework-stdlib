<?php

namespace Forte\Stdlib\Exceptions;

/**
 * Trait ThrowErrorsTrait.
 *
 * Methods to easily throw exceptions.
 *
 * @package Forte\Stdlib\Exceptions
 */
trait ThrowErrorsTrait
{
    /**
     * Throw a GeneralException with the given message and parameters.
     *
     * @param string $message The exception message.
     * @param string[] $parameters The values to replace in the error message.
     *
     * @throws GeneralException
     */
    public function throwGeneralException(string $message, string ...$parameters): void
    {
        throw $this->getGeneralException($message, ...$parameters);
    }

    /**
     * Return a GeneralException with the given message and parameters.
     *
     * @param string $message The exception message.
     * @param string[] $parameters The values to replace in the error message.
     *
     * @return GeneralException
     */
    public function getGeneralException(string $message, string ...$parameters): GeneralException
    {
        return new GeneralException(vsprintf($message, $parameters));
    }

    /**
     * Throw a MissingKeyException with the given message and parameters.
     *
     * @param string $key The missing key.
     * @param string $message The exception message.
     * @param string[] $parameters The values to replace in the error message.
     *
     * @throws MissingKeyException
     */
    public function throwMissingKeyException(string $key, string $message, string ...$parameters): void
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
     * @return MissingKeyException
     */
    public function getMissingKeyException(string $key, string $message, string ...$parameters): MissingKeyException
    {
        return new MissingKeyException($key, vsprintf($message, $parameters));
    }

}