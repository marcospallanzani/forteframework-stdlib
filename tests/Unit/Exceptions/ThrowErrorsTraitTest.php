<?php
/**
 * This file is part of the ForteFramework package.
 *
 * Copyright (c) 2019  Marco Spallanzani <marco@forteframework.com>
 *
 *  For the full copyright and license information,
 *  please view the LICENSE file that was distributed
 *  with this source code.
 */

namespace Forte\Stdlib\Tests\Unit\Exceptions;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\MissingKeyException;
use Forte\Stdlib\Tests\Unit\BaseTest;

/**
 * Class ThrowErrorsTraitTest.
 *
 * @package Tests\Unit\Helpers
 */
class ThrowErrorsTraitTest extends BaseTest
{
    /**
     * Test ThrowErrorsTrait::throwGeneralException() method.
     *
     * @throws GeneralException
     */
    public function testThrowGeneralException(): void
    {
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage("error message test.");
        $this->getAnonymousClass()->throwGeneralException(self::BASE_TEST_MESSAGE, "test");
    }

    /**
     * Test ThrowErrorsTrait::getGeneralException() method.
     */
    public function testGetGeneralException(): void
    {
        $anonymousActionClass = $this->getAnonymousClass();
        $generalException = $anonymousActionClass->getGeneralException(
            self::BASE_TEST_MESSAGE,
            'test'
        );
        $this->assertInstanceOf(GeneralException::class, $generalException);
        $this->assertEquals('error message test.', $generalException->getMessage());
        $this->assertEquals(
            [
                'error_message' => 'error message test.',
                'error_code'    => 0
            ],
            $generalException->toArray()
        );
    }

    /**
     * Test ThrowErrorsTrait::throwMissingKeyException() method.
     *
     * @throws MissingKeyException
     */
    public function testThrowMissingKeyException(): void
    {
        $this->expectException(MissingKeyException::class);
        $this->expectExceptionMessage("error message test.");
        $this->getAnonymousClass()->throwMissingKeyException("test_key", self::BASE_TEST_MESSAGE, "test");
    }

    /**
     * Test ThrowErrorsTrait::getMissingKeyException() method.
     */
    public function testGetMissingKeyException(): void
    {
        $anonymousActionClass = $this->getAnonymousClass();
        $missingKeyException = $anonymousActionClass->getMissingKeyException(
            'test_key',
            self::BASE_TEST_MESSAGE,
            'test'
        );
        $this->assertInstanceOf(MissingKeyException::class, $missingKeyException);
        $this->assertEquals('error message test.', $missingKeyException->getMessage());
        $this->assertEquals(
            [
                'missing_key'   => 'test_key',
                'error_message' => 'error message test.',
                'error_code'    => 0
            ],
            $missingKeyException->toArray()
        );
    }
}
