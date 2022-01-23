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

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\FileTrait;

/**
 * @package Forte\Stdlib\Tests\Unit
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class FileTraitTest extends BaseTest
{
    /**
     * Returns an anonymous instance that uses the FileTraitTest.
     *
     * @return object
     */
    protected function getAnonymousTestClass()
    {
        return new class {
            use FileTrait;
        };
    }

    /**
     * Data provider for all file-exists tests.
     *
     * @return array
     */
    public function filesProvider(): array
    {
        return [
            [__DIR__ . '/../data/config/parsetest.ini', true, false, false],
            [__DIR__ . '/../data/config/parsetest.ini', true, true, false],
            [__DIR__ . '/../data/config/parsetest.json', true, false, false],
            [__DIR__ . '/../data/config/parsetest.json', true, true, false],
            [__DIR__ . '/../data/config/parsetest', false, false, false],
            [__DIR__ . '/../data/config/parsetest', false, true, true],
        ];
    }

    /**
     * Tests the FileTrait::checkFileExists() function.
     *
     * @dataProvider filesProvider
     *
     * @param string $filePath The file path to be checked.
     * @param bool $expected Whether the given file path is associated to an existing file.
     * @param bool $raiseError Whether an error should be raised while checking if the file exists.
     * @param bool $exceptionExpected Whether an exception is expected.
     */
    public function testFileExists(
        string $filePath,
        bool $expected,
        bool $raiseError,
        bool $exceptionExpected
    ): void
    {
        $class = $this->getAnonymousTestClass();
        if ($exceptionExpected) {
            $this->expectException(GeneralException::class);
        }
        $this->assertEquals($expected, $class->fileExists($filePath, $raiseError));
    }
}
