<?php

namespace Forte\Stdlib\Exceptions;

use Forte\Stdlib\ArrayableInterface;

/**
 * Class GeneralException.
 *
 * Base exception for all ForteFramework based projects/classes/scripts.
 *
 * @package Forte\Stdlib\Exceptions
 */
class GeneralException extends \Exception implements ArrayableInterface
{
    /**
     * Return an array representation of this GeneralException instance.
     *
     * @return array Array representation of this GeneralException instance.
     */
    public function toArray(): array
    {
        $array = [];

        // The error message
        $array['error_message'] = $this->message;

        // The error code
        $array['error_code'] = $this->code;

        return $array;
    }
}
