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

namespace Forte\Stdlib\Tests\Unit\Filters\Files;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Filters\Files\Copy;
use Forte\Stdlib\Tests\Unit\BaseTest;

/**
 * @package Forte\Stdlib\Tests\Unit\Filters\Files
 */
class CopyTest extends BaseTest
{
    /**
     * Temporary files constants
     */
    public const TEST_FILE_TMP = __DIR__ . DIRECTORY_SEPARATOR . 'file-tests';
    public const TEST_FILE_TMP_COPY = __DIR__ . DIRECTORY_SEPARATOR . 'file-tests_COPY';
    public const TEST_CONTENT = 'ANY CONTENT';
    public const TEST_WRONG_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'path-to-non-existent-file.php';

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        @file_put_contents(self::TEST_FILE_TMP, self::TEST_CONTENT);
    }

    /**
     * This method is called after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();
        @unlink(self::TEST_FILE_TMP);
        @unlink(self::TEST_FILE_TMP_COPY);
    }

    /**
     * Test the method Forte\Stdlib\Filters\Files\Copy::filter().
     *
     * @throws GeneralException
     */
    public function testFilter(): void
    {
        $copyFilter = new Copy([
            'target' => self::TEST_FILE_TMP_COPY,
            'overwrite' => true,
        ]);
        $copyFilter->filter(self::TEST_FILE_TMP);
        $this->assertEquals(self::TEST_CONTENT, file_get_contents(self::TEST_FILE_TMP_COPY));
    }

    /**
     * Test the method Forte\Stdlib\Filters\Files\Copy::filter() on failure.
     *
     * @throws GeneralException
     */
    public function testFilterFail(): void
    {
        $copyFilter = new Copy([
            'target' => self::TEST_FILE_TMP_COPY,
            'overwrite' => true,
        ]);
        $copyFilter->filter(self::TEST_WRONG_FILE);
        $this->assertEquals(false, @file_get_contents(self::TEST_FILE_TMP_COPY));
    }

    /**
     * Test the method Forte\Stdlib\Filters\Files\Copy::filter() on failure.
     *
     * @throws GeneralException
     */
    public function testFilterScalarFail(): void
    {
        $copyFilter = new Copy([
            'target' => self::TEST_FILE_TMP_COPY,
            'overwrite' => true,
        ]);
        $this->assertEquals(null, $copyFilter->filter(null));
    }

    /**
     * Test the method Forte\Stdlib\Filters\Files\Copy::filter() on runtime failure.
     */
    public function testFilterRuntimeFail(): void
    {
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage("An error occurred while copying file " . self::TEST_FILE_TMP_COPY . ".");
        $copyFilterMock = \Mockery::mock(Copy::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $copyFilterMock
            ->shouldReceive('getNewName')
            ->once()
            ->andReturn([
                'target' => self::TEST_FILE_TMP_COPY,
                'source' => self::TEST_FILE_TMP_COPY,
            ])
            ->shouldReceive('copyFileToDestination')
            ->once()
            ->andReturn(false)
        ;
        $copyFilterMock->filter(self::TEST_WRONG_FILE);
    }
}
