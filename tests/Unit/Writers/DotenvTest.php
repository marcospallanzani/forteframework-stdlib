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

namespace Forte\Stdlib\Tests\Unit\Writers;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\FileUtils;
use Forte\Stdlib\Tests\Unit\BaseTest;
use Forte\Stdlib\Writers\Dotenv as DotenvWriter;

/**
 * @package Forte\Stdlib\Tests\Unit\Writers
 */
class DotenvTest extends BaseTest
{
    /**
     * Data provider for .env tests.
     *
     * @return array
     */
    public function envDataProvider(): array
    {
        $envData = [
            'key1' => 'value1',
            'key3' => true,
            'key5' => false,
            'key7' => 99,
            'key9' => 99.99,
        ];

        $wrongData = array_merge($envData, ['key11' => new \stdClass()]);

        $testWriterFilePath = \implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'data', 'config', '.env.test-writer']);

        // File path | data to write | success | exception
        return [
            [$testWriterFilePath, $envData, true, false],
            [$testWriterFilePath, $wrongData, false, true],
        ];
    }

    /**
     * Test function Writers\Dotenv::toFile().
     *
     * @dataProvider envDataProvider
     *
     * @param string $filePath The output file path.
     * @param array $data The data to be written to the output file.
     * @param bool $success Whether the write action should be successful.
     * @param bool $exceptionExpected Whether an exception should be expected.
     *
     * @throws GeneralException
     */
    public function testWriteToFile(string $filePath, array $data, bool $success, bool $exceptionExpected): void
    {
        if ($exceptionExpected) {
            $this->expectException(GeneralException::class);
            $this->expectExceptionMessage('Objects are not accepted as a possible value in a .env file.');
        }
        $dotenvWriter = new DotenvWriter();
        $dotenvWriter->toFile($filePath, $data);
        if ($success) {
            $this->assertFileExists($filePath);
            $this->assertEquals($data, FileUtils::parseFile($filePath, FileUtils::CONTENT_TYPE_ENV));
        }
    }
}
