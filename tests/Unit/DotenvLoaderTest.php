<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\DotenvLoader;
use Forte\Stdlib\Exceptions\GeneralException;

/**
 * Class DotenvLoaderTest.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class DotenvLoaderTest extends BaseTest
{
    /**
     * Test function DotenvLoader::loadIntoArray().
     *
     * @throws GeneralException
     */
    public function testLoadIntoArray(): void
    {
        $this->assertEquals(
            $this->configEnvArray,
            DotenvLoader::loadIntoArray(__DIR__ . "/../data/configfiles/.env.parsetest")
        );
    }

    /**
     * Test function DotenvLoader::loadIntoArray() with an empty file path: error expected.
     *
     * @throws GeneralException
     */
    public function testLoadIntoArrayEmptyFilePath(): void
    {
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('The file path must be provided.');
        DotenvLoader::loadIntoArray('');
    }

    /**
     * Test function DotenvLoader::loadIntoArray() with a non-existent file: error expected.
     *
     * @throws GeneralException
     */
    public function testLoadIntoArrayNonExistentFile(): void
    {
        $filePath = __DIR__ . "/../data/configfiles/xxx";
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage("Unable to read the environment file [$filePath].");
        DotenvLoader::loadIntoArray($filePath);
    }

    /**
     * Test function DotenvLoader::loadIntoArray() with a badly-formed file: error expected.
     *
     * @throws GeneralException
     */
    public function testLoadIntoArrayBadlyFormedFile(): void
    {
        $filePath = __DIR__ . "/../data/configfiles/.env.wrong";
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage(
            "Error occurred while reading the file '$filePath'. Error message is: Failed " .
            "to parse dotenv file due to an unexpected equals. Failed at [=value3]."
        );
        DotenvLoader::loadIntoArray($filePath);
    }

    /**
     * Test function DotenvLoader::getLineFromVariables().
     */
    public function testGetLineFromVariables(): void
    {
        $this->assertEquals("key=value", DotenvLoader::getLineFromVariables('key', 'value'));
    }
}