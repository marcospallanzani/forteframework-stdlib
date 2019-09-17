<?php

namespace Forte\Stdlib\Tests\Unit\Writers;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\FileUtils;
use Forte\Stdlib\Tests\Unit\BaseTest;
use Forte\Stdlib\Writers\Dotenv as DotenvWriter;

/**
 * Class DotenvTest.
 *
 * @package Forte\Stdlib\Tests\Unit\Writers
 */
class DotenvTest extends BaseTest
{
    /**
     * Test constants
     */
    const TEST_WRITER_FILE_PATH = __DIR__ . "/../../data/configfiles/.env.test-writer";

    /**
     * This method is called after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();
        @unlink(self::TEST_WRITER_FILE_PATH);
    }

    /**
     * Data provider for .env tests.
     *
     * @return array
     */
    public function envDataProvider(): array
    {
        $envData = [
            "key1" => "value1",
            "key3" => true,
            "key5" => false,
            "key7" => 99,
            "key9" => 99.99,
        ];

        $wrongData = array_merge($envData, ['key11' => new \stdClass()]);

        // File path | data to write | success | exception
        return [
            [self::TEST_WRITER_FILE_PATH, $envData, true, false],
            [self::TEST_WRITER_FILE_PATH, $wrongData, false, true],
        ];
    }

    /**
     * Test function Writers\Dotenv::toFile().
     *
     * @dataProvider envDataProvider
     *
     * @param string $filePath
     * @param $data
     * @param $success
     * @param $exceptionExpected
     *
     * @throws GeneralException
     */
    public function testWriteToFile(string $filePath, $data, $success, $exceptionExpected): void
    {
        if ($exceptionExpected) {
            $this->expectException(GeneralException::class);
            $this->expectExceptionMessage("Objects are not accepted as a possible value in a .env file.");
        }
        $dotenvWriter = new DotenvWriter();
        $dotenvWriter->toFile($filePath, $data);
        if ($success) {
            $this->assertFileExists($filePath);
            $this->assertEquals($data, FileUtils::parseFile($filePath, FileUtils::CONTENT_TYPE_ENV));
        }
    }
}
