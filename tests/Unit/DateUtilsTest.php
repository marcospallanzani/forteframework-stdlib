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

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\DateUtils;

/**
 * @package Forte\Stdlib\Tests\Unit
 */
class DateUtilsTest extends BaseTest
{
    /**
     * Test function DateUtils::formatMicroTime().
     */
    public function testFormatMicroTime(): void
    {
        $this->assertEquals('2019-09-17 15:38:08.514400 +00:00', DateUtils::formatMicroTime(1568734688.514400));
    }
}
