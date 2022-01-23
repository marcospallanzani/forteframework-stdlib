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

namespace Forte\Stdlib\Tests\Unit\Exceptions;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\MissingKeyException;
use Forte\Stdlib\Exceptions\WrongParameterException;
use Forte\Stdlib\Tests\Unit\BaseTest;

/**
 * @package Tests\Unit\Helpers
 * @author  Marco Spallanzani <forteframework@gmail.com>
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
        $this->expectExceptionMessage('error message test.');
        $this->getAnonymousClass()->throwGeneralException(self::BASE_TEST_MESSAGE, 'test');
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
                'error_code'    => 0,
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
        $this->expectExceptionMessage('error message test.');
        $this->getAnonymousClass()->throwMissingKeyException('test_key', self::BASE_TEST_MESSAGE, 'test');
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
                'error_code'    => 0,
            ],
            $missingKeyException->toArray()
        );
    }

    /**
     * Test ThrowErrorsTrait::throwWrongParameterException() method.
     *
     * @throws WrongParameterException
     */
    public function testThrowWrongParameterException(): void
    {
        $this->expectException(WrongParameterException::class);
        $this->expectExceptionMessage('error message test.');
        $this->getAnonymousClass()->throwWrongParameterException(self::BASE_TEST_MESSAGE, 'test');
    }

    /**
     * Test ThrowErrorsTrait::getWrongParameterException() method.
     */
    public function testGetWrongParameterException(): void
    {
        $anonymousActionClass = $this->getAnonymousClass();
        $wrongParameterException = $anonymousActionClass->getWrongParameterException(
            self::BASE_TEST_MESSAGE,
            'test'
        );
        $this->assertInstanceOf(WrongParameterException::class, $wrongParameterException);
        $this->assertEquals('error message test.', $wrongParameterException->getMessage());
        $this->assertEquals(
            [
                'error_message' => 'error message test.',
                'error_code'    => 0,
            ],
            $wrongParameterException->toArray()
        );
    }
}
