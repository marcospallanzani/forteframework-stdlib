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

/**
 * Methods to handle Date objects.
 *
 * @package Forte\Stdlib
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class DateUtils
{
    //TODO check PHP8 format
    /**
     * Format constants
     */
    public const DATE_FORMAT_FULL_MICRO_TIME = 'Y-m-d H:i:s.u e';

    /**
     * Convert the given micro time to a date string, by using the given date format.
     *
     * @param float $microTime The micro time to be converted.
     * @param string $format The date format to be used.
     *
     * @return string A formatted data string representing the given micro time.
     */
    public static function formatMicroTime(
        float $microTime,
        string $format = self::DATE_FORMAT_FULL_MICRO_TIME
    ): string
    {
        return date_create_from_format(
            'U.u',
            number_format(
                $microTime,
                6,
                '.',
                ''
            )
        )->format($format);
    }
}
