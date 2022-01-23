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

use Forte\Stdlib\ArrayableInterface;

/**
 * Base exception for all ForteFramework based projects/classes/scripts.
 *
 * @package Forte\Stdlib\Exceptions
 * @author  Marco Spallanzani <forteframework@gmail.com>
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
