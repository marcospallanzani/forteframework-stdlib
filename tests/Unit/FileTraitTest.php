<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\FileTrait;

/**
 * Class FileTraitTest.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class FileTraitTest extends BaseTest
{
    /**
     * Returns an anonymous class to test ClassAccessTrait.
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
            [__DIR__ . '/../data/configfiles/parsetest.ini', true, false, false],
            [__DIR__ . '/../data/configfiles/parsetest.ini', true, true, false],
            [__DIR__ . '/../data/configfiles/parsetest.json', true, false, false],
            [__DIR__ . '/../data/configfiles/parsetest.json', true, true, false],
            [__DIR__ . '/../data/configfiles/parsetest', false, false, false],
            [__DIR__ . '/../data/configfiles/parsetest', false, true, true],
        ];
    }

    /**
     * Tests the FileTrait::checkFileExists() function.
     *
     * @dataProvider filesProvider
     *
     * @param string $filePath
     * @param bool $expected
     * @param bool $raiseError
     * @param bool $exceptionExpected
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
