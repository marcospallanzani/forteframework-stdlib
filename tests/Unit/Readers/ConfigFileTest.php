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

namespace Forte\Stdlib\Tests\Unit\Readers;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\MissingKeyException;
use Forte\Stdlib\FileUtils;
use Forte\Stdlib\Readers\ConfigFile;
use Forte\Stdlib\Tests\Unit\BaseTest;

/**
 * @package Forte\Stdlib\Tests\Unit\Readers
 */
class ConfigFileTest extends BaseTest
{
    /**
     * Test function ConfigFile::getConfigFilePath().
     *
     * @dataProvider configFilesProvider
     *
     * @param string $filePath The configuration file path.
     * @param string $contentType The content type of the given configuration file.
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
     * @param string $filePath The configuration file path.
     * @param string $contentType The content type of the given configuration file.
     * @param array $expectedContent The expected content.
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
     * @param string $filePath The configuration file path.
     * @param string $contentType The content type of the given configuration file.
     * @param array $expectedContent The expected content.
     * @param array $existentKeys The keys that should exist in the parsed content.
     *
     * @throws GeneralException
     * @throws MissingKeyException
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
     * @param string $filePath The configuration file path.
     * @param string $contentType The content type of the given configuration file.
     * @param array $expectedContent The expected content.
     * @param array $existentKeys The keys that should exist in the parsed content.
     * @param array $nonExistentKeys The keys that should not exist in the parsed content.
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
     * @throws GeneralException
     */
    public function testContentTypeNotSupported(): void
    {
        $this->expectException(GeneralException::class);
        new ConfigFile(__DIR__ . '/../data/config/empty_parsetest.json', FileUtils::CONTENT_TYPE_JSON);
    }
}
