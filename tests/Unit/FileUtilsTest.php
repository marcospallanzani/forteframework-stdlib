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
use Forte\Stdlib\FileUtils;

/**
 * @package Forte\Stdlib\Tests\Unit
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class FileUtilsTest extends BaseTest
{
    /**
     * This method is called after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();
        @unlink(__DIR__ . '/../data/config/simple-config.ini');
        @unlink(__DIR__ . '/../data/config/simple-config.json');
        @unlink(__DIR__ . '/../data/config/simple-config.php');
        @unlink(__DIR__ . '/../data/config/simple-config.xml');
        @unlink(__DIR__ . '/../data/config/simple-config.yml');
        @unlink(__DIR__ . '/../data/config/.env.simple-config');
    }

    /**
     * Data provider for all file-utils tests.
     *
     * @return array
     */
    public function fileUtilsProvider(): array
    {
        $now = time();
        $expectedArray = [
            "key1$now" => "value1$now",
            "key2$now" => [
                "key3$now" => "value3$now",
                "key4$now" => [
                    "key5$now" => "value5$now",
                ],
            ],
        ];

        $expectedEnvArray = [
            "key1$now" => "value1$now",
            "key3$now" => "value3$now",
            "key5$now" => "value5$now",
        ];

        return [
            // file path    |   content type    |   content     |       expect an exception
            [__DIR__ . '/../data/config/simple-config.ini', FileUtils::CONTENT_TYPE_INI, $expectedArray, false],
            [__DIR__ . '/../data/config/simple-config.json', FileUtils::CONTENT_TYPE_JSON, $expectedArray, false],
            [__DIR__ . '/../data/config/simple-config.php', FileUtils::CONTENT_TYPE_ARRAY, $expectedArray, false],
            [__DIR__ . '/../data/config/simple-config.xml', FileUtils::CONTENT_TYPE_XML, $expectedArray, false],
            [__DIR__ . '/../data/config/simple-config.yml', FileUtils::CONTENT_TYPE_YAML, $expectedArray, false],
            [__DIR__ . '/../data/config/.env.simple-config', FileUtils::CONTENT_TYPE_ENV, $expectedEnvArray, false],
            ['', FileUtils::CONTENT_TYPE_INI, $expectedArray, true],
            ['', FileUtils::CONTENT_TYPE_JSON, $expectedArray, true],
            [__DIR__ . '', FileUtils::CONTENT_TYPE_ARRAY, $expectedArray, true],
            [__DIR__ . '', FileUtils::CONTENT_TYPE_XML, $expectedArray, true],
            [__DIR__ . '/../data/config/simple-config', 'text', [], false],
        ];
    }

    /**
     * @return array
     */
    public function emptyFilesProvider(): array
    {
        return [
            [__DIR__ . '/data/empty_parsetest.json', FileUtils::CONTENT_TYPE_JSON],
            [__DIR__ . '/data/empty_parsetest.json', FileUtils::CONTENT_TYPE_XML],
        ];
    }

    /**
     * Data provider for export tests.
     *
     * @return array
     *
     * @throws GeneralException
     */
    public function exportFileProvider(): array
    {
        // Content | content type | destination full path | destination path prefix | export dir path | exception expected
        $tests = [];
        $tests = array_merge($tests, $this->getExportVariables($this->configFileArray, FileUtils::CONTENT_TYPE_JSON));
        $tests = array_merge($tests, $this->getExportVariables($this->configFileArray, FileUtils::CONTENT_TYPE_YAML));
        $tests = array_merge($tests, $this->getExportVariables($this->configFileArray, FileUtils::CONTENT_TYPE_XML));
        $tests = array_merge($tests, $this->getExportVariables($this->configFileArray, FileUtils::CONTENT_TYPE_ARRAY));
        $tests = array_merge($tests, $this->getExportVariables($this->configFileArray, FileUtils::CONTENT_TYPE_INI));
        $tests = array_merge($tests, $this->getExportVariables($this->configEnvArray, FileUtils::CONTENT_TYPE_ENV));

        return $tests;
    }

    /**
     * Test the FileUtils::parseConfigFile() and FileUtils::writeToConfigFile() functions.
     *
     * @dataProvider fileUtilsProvider
     *
     * @param string $filePath The output file path.
     * @param string $contentType The output file content type.
     * @param array $content The content to be written to the specified path.
     * @param bool $expectException Whether an exception is expected.
     *
     * @throws GeneralException
     */
    public function testWriteAndParseConfigFile(
        string $filePath,
        string $contentType,
        array $content,
        bool $expectException
    ): void
    {
        if ($expectException) {
            $this->expectException(GeneralException::class);
        }
        FileUtils::writeToFile($content, $filePath, $contentType);
        $parsedArray = FileUtils::parseFile($filePath, $contentType);
        $this->assertEquals($content, $parsedArray);
    }

    /**
     * If an empty json file is parsed, a Runtime exception should be thrown.
     *
     * @dataProvider emptyFilesProvider
     *
     * @param string $filePath The path of the file to be parsed.
     * @param string $contentType The file content type.
     *
     * @throws GeneralException
     */
    public function testParseExpectRuntimeException(string $filePath, string $contentType): void
    {
        $this->expectException(GeneralException::class);
        FileUtils::parseFile($filePath, $contentType);
    }

    /**
     * Check the supported list of content type.
     */
    public function testSupportedContentTypes(): void
    {
        $constants = FileUtils::getSupportedContentTypes();
        $this->assertIsArray($constants);
        $this->assertCount(6, $constants);
        $this->assertArrayHasKey('CONTENT_TYPE_JSON', $constants);
        $this->assertArrayHasKey('CONTENT_TYPE_INI', $constants);
        $this->assertArrayHasKey('CONTENT_TYPE_YAML', $constants);
        $this->assertArrayHasKey('CONTENT_TYPE_XML', $constants);
        $this->assertArrayHasKey('CONTENT_TYPE_ARRAY', $constants);
        $this->assertArrayHasKey('CONTENT_TYPE_ENV', $constants);
    }

    /**
     * Test function FileUtils::getFileExtensionByContentType().
     *
     * @throws GeneralException
     */
    public function testFileExtensions(): void
    {
        $this->assertEquals('ini', FileUtils::getFileExtensionByContentType(FileUtils::CONTENT_TYPE_INI));
        $this->assertEquals('json', FileUtils::getFileExtensionByContentType(FileUtils::CONTENT_TYPE_JSON));
        $this->assertEquals('php', FileUtils::getFileExtensionByContentType(FileUtils::CONTENT_TYPE_ARRAY));
        $this->assertEquals('yml', FileUtils::getFileExtensionByContentType(FileUtils::CONTENT_TYPE_YAML));
        $this->assertEquals('xml', FileUtils::getFileExtensionByContentType(FileUtils::CONTENT_TYPE_XML));
        $this->assertEquals('', FileUtils::getFileExtensionByContentType(FileUtils::CONTENT_TYPE_ENV));
    }

    /**
     * Test function FileUtils::getFileExtensionByContentType() with a wrong content type.
     *
     * @throws GeneralException
     */
    public function testFileExtensionsWrongContentType(): void
    {
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('Content type not supported.');
        $this->assertEquals('', FileUtils::getFileExtensionByContentType('wrong_content_type'));
    }

    /**
     * Test function FileUtils::getContentTypeByFileExtension().
     *
     * @throws GeneralException
     */
    public function testContentTypes(): void
    {
        $this->assertEquals(FileUtils::CONTENT_TYPE_INI, FileUtils::getContentTypeByFileExtension('ini'));
        $this->assertEquals(FileUtils::CONTENT_TYPE_JSON, FileUtils::getContentTypeByFileExtension('json'));
        $this->assertEquals(FileUtils::CONTENT_TYPE_ARRAY, FileUtils::getContentTypeByFileExtension('php'));
        $this->assertEquals(FileUtils::CONTENT_TYPE_YAML, FileUtils::getContentTypeByFileExtension('yml'));
        $this->assertEquals(FileUtils::CONTENT_TYPE_XML, FileUtils::getContentTypeByFileExtension('xml'));
        $this->assertEquals(FileUtils::CONTENT_TYPE_ENV, FileUtils::getContentTypeByFileExtension(''));
    }

    /**
     * Test function FileUtils::getContentTypeByFileExtension() with a wrong file extension.
     *
     * @throws GeneralException
     */
    public function testContentTypesWrongExtension(): void
    {
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('File extension not supported.');
        $this->assertEquals('', FileUtils::getContentTypeByFileExtension('wrong_extension'));
    }

    /**
     * Test function FileUtils::exportArrayReportToFile().
     *
     * @dataProvider exportFileProvider
     *
     * @param array $content The content to be exported.
     * @param string $contentType The content type.
     * @param string $exportFullFilePath The full path of the export file.
     * @param string $defaultNamePrefix The default file name prefix to be used.
     * @param string $exportDirPath The export directory path.
     * @param bool $expectedException Whether an exception is expected.
     * @param string $exceptionMessage The expected exception message.
     *
     * @throws GeneralException
     */
    public function testExportArrayReportToFile(
        array $content,
        string $contentType,
        string $exportFullFilePath,
        string $defaultNamePrefix,
        string $exportDirPath,
        bool $expectedException,
        string $exceptionMessage
    ): void
    {
        if ($expectedException) {
            $this->expectException(GeneralException::class);
            $this->expectExceptionMessage($exceptionMessage);
        }

        // We write the content
        $writtenFile = FileUtils::exportArrayReportToFile(
            $content,
            $contentType,
            $exportFullFilePath,
            $defaultNamePrefix,
            $exportDirPath
        );

        // We check the written content
        if (FileUtils::CONTENT_TYPE_XML === $contentType) {
            $content = ['element' => $content];
        }
        $this->assertEquals($content, FileUtils::parseFile($writtenFile, $contentType));

        // We check the prefix of the exported file, in case no full export path was provided
        if ('' === $exportFullFilePath) {
            if ('' !== $defaultNamePrefix) {
                $this->assertStringContainsString($defaultNamePrefix, $writtenFile);
            } else {
                $this->assertStringContainsString('export_data', $writtenFile);
            }
        }

        @unlink($writtenFile);
    }

    /**
     * Test function FileUtils::appendContentTypeExtension().
     *
     * @throws GeneralException
     */
    public function testAppendContentTypeExtension(): void
    {
        $this->assertEquals('test.json', FileUtils::appendContentTypeExtension('test', FileUtils::CONTENT_TYPE_JSON));
        $this->assertEquals('test.xml', FileUtils::appendContentTypeExtension('test', FileUtils::CONTENT_TYPE_XML));
        $this->assertEquals('test.yml', FileUtils::appendContentTypeExtension('test', FileUtils::CONTENT_TYPE_YAML));
        $this->assertEquals('test.ini', FileUtils::appendContentTypeExtension('test', FileUtils::CONTENT_TYPE_INI));
        $this->assertEquals('test.php', FileUtils::appendContentTypeExtension('test', FileUtils::CONTENT_TYPE_ARRAY));
        $this->assertEquals('test', FileUtils::appendContentTypeExtension('test', FileUtils::CONTENT_TYPE_ENV));

        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('Content type not supported.');
        FileUtils::appendContentTypeExtension('test', 'wrong-content-type');
    }

    /**
     * Return a list of test cases for the given content and content type.
     *
     * @param array $content An array representation of the content to be exported to a file.
     * @param string $contentType The output content file type.
     *
     * @return array
     *
     * @throws GeneralException
     */
    protected function getExportVariables(array $content, string $contentType): array
    {
        if (FileUtils::CONTENT_TYPE_ENV !== $contentType) {
            $exportFilePath = '/../data/config/export-to-file.' .
                FileUtils::getFileExtensionByContentType($contentType);
        } else {
            $exportFilePath = '/../data/config/.env.export-to-file';
        }
        return [
            [$content, $contentType, __DIR__ . $exportFilePath, '', '', false, ''],
            [$content, $contentType, '', 'test-prefix-unique', '', false, ''],
            [$content, $contentType, '', 'export_data', '', false, ''],
            [$content, $contentType, '', 'test-prefix-unique', __DIR__ . '/../data', false, ''],
            [$content, $contentType, __DIR__, '', '', true, 'The given destination file path cannot be a directory.'],
        ];
    }
}
