<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\DateUtils;

/**
 * Class DateUtils.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class DateUtilsTest extends BaseTest
{
    /**
     * Test function DateUtils::formatMicroTime().
     */
    public function testFormatMicroTime(): void
    {
        $this->assertEquals("2019-09-17 15:38:08.514400 +00:00", DateUtils::formatMicroTime(1568734688.514400));
    }
}