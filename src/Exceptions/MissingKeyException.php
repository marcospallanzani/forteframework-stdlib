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

use Throwable;

/**
 * Exception used to throw an error when a required key is missing
 * in a given data structure (e.g. array, configuration file, etc.).
 *
 * @package Forte\Stdlib\Exceptions
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class MissingKeyException extends GeneralException
{
    /**
     * The missing key.
     *
     * @var string
     */
    protected $missingKey;

    /**
     * @param string $missingKey The missing key that thrown this exception.
     * @param string $message The Exception message to throw.
     * @param int $code The Exception code.
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $missingKey, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->missingKey = $missingKey;
    }

    /**
     * Returns the missing config key.
     *
     * @return string The missing key.
     */
    public function getMissingKey(): string
    {
        return $this->missingKey;
    }

    /**
     * Return an array representation of this MissingKeyException instance.
     *
     * @return array Array representation of this MissingKeyException instance.
     */
    public function toArray(): array
    {
        $array = [];

        // The missing key
        $array['missing_key'] = $this->missingKey;

        // The error message
        $array['error_message'] = $this->message;

        // The error code
        $array['error_code'] = $this->code;

        return $array;
    }
}
