<?php

namespace Forte\Stdlib\Tests\Unit\Readers;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\MissingKeyException;
use Forte\Stdlib\FileUtils;
use Forte\Stdlib\Readers\ConfigFile;
use Forte\Stdlib\Tests\Unit\BaseTest;

/**
 * Class ConfigFileTest.
 *
 * @package Forte\Stdlib\Tests\Unit\Readers
 */
class ConfigFileTest extends BaseTest
{
    /**
     * Test function ConfigFile::getConfigFilePath().
     *
     * @dataProvider configFilesProvider
     *
     * @param string $filePath
     * @param string $contentType
     *
     * @throws GeneralException
     */
    public function testGetConfigFilePath(string $filePath, string $contentType): void
    {
        $configFile = new ConfigFile($filePath, $contentType);
        $this->assertEquals($filePath, $configFile->getConfigFilePath());
    }

    /**
     * Test function ConfigFile::getConfigFilePath().
     *
     * @dataProvider configFilesProvider
     *
     * @param string $filePath
     * @param string $contentType
     * @param array $expectedContent
     *
     * @throws GeneralException
     */
    public function testToArray(string $filePath, string $contentType, array $expectedContent): void
    {
        $configFile = new ConfigFile($filePath, $contentType);
        $this->assertEquals(
            ['configEntries' => $expectedContent, 'configFilePath' => $filePath],
            $configFile->toArray()
        );
    }

    /**
     * Test function ConfigFile::getValue().
     *
     * @dataProvider configFilesProvider
     *
     * @param string $filePath
     * @param string $contentType
     *
     * @throws GeneralException
     */
    public function testGetValue(
        string $filePath,
        string $contentType,
        array $expectedContent,
        array $existentKeys
    ): void
    {
        $configFile = new ConfigFile($filePath, $contentType);
        foreach ($existentKeys as $key => $value) {
            $this->assertEquals($value, $configFile->getValue($key));
        }
    }

    /**
     * Test function ConfigFile::getValue() for non-existent keys.
     *
     * @dataProvider configFilesProvider
     *
     * @param string $filePath
     * @param string $contentType
     * @param array $expectedContent
     * @param array $existentKeys
     * @param array $nonExistentKeys
     *
     * @throws GeneralException
     * @throws MissingKeyException
     */
    public function testGetValueForNonExistentKeys(
        string $filePath,
        string $contentType,
        array $expectedContent,
        array $existentKeys,
        array $nonExistentKeys
    ): void
    {
        $this->expectException(MissingKeyException::class);
        $configFile = new ConfigFile($filePath, $contentType);
        foreach ($nonExistentKeys as $key) {
            $this->expectExceptionMessage("Array key '$key' not found.");
            $configFile->getValue($key);
        }
    }

    /**
     * Test function ConfigFile::__construct() with a wrong file.
     *
     * @dataProvider configFilesProvider
     *
     * @param string $filePath
     *
     * @throws GeneralException
     */
    public function testContentTypeNotSupported(string $filePath): void
    {
        $this->expectException(GeneralException::class);
        new ConfigFile(__DIR__ . "/../data/configfiles/empty_parsetest.json", FileUtils::CONTENT_TYPE_JSON);
    }
}
