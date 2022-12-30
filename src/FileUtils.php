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

namespace Forte\Stdlib;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Writers\Dotenv as DotenvWriter;
use Laminas\Config\Exception\RuntimeException;
use Laminas\Config\Reader\Ini as IniReader;
use Laminas\Config\Reader\Json as JsonReader;
use Laminas\Config\Reader\Xml as XmlReader;
use Laminas\Config\Writer\Ini as IniWriter;
use Laminas\Config\Writer\Json as JsonWriter;
use Laminas\Config\Writer\PhpArray;
use Laminas\Config\Writer\Xml as XmlWriter;
use Symfony\Component\Yaml\Yaml as YamlReader;

/**
 * Utility class for handling various file actions.
 *
 * @package Forte\Stdlib
 */
class FileUtils
{
    use ClassAccessTrait;

    /**
     * Supported content types.
     */
    public const CONTENT_TYPE_JSON = 'content_json';
    public const CONTENT_TYPE_INI = 'content_ini';
    public const CONTENT_TYPE_YAML = 'content_yaml';
    public const CONTENT_TYPE_XML = 'content_xml';
    public const CONTENT_TYPE_ARRAY = 'content_array';
    public const CONTENT_TYPE_ENV = 'content_env';

    /**
     * Parse the given file path and return its content as an array.
     *
     * @param string $filePath The file to be parsed.
     * @param string $contentType The content type (supported types are the
     * constants whose name starts with the prefix 'CONTENT_TYPE').
     *
     * @return array<mixed, mixed> An array representing the given file path.
     *
     * @throws GeneralException If an error occurred while parsing the file
     * (e.g. json syntax not respected).
     */
    public static function parseFile(string $filePath, string $contentType): array
    {
        try {
            $parsedContent = null;
            switch ($contentType) {
                case self::CONTENT_TYPE_INI:
                    $iniReader = new IniReader();
                    $parsedContent = $iniReader->fromFile($filePath);
                    break;
                case self::CONTENT_TYPE_YAML:
                    $parsedContent = YamlReader::parseFile($filePath);
                    break;
                case self::CONTENT_TYPE_JSON:
                    $jsonReader = new JsonReader();
                    $parsedContent = $jsonReader->fromFile($filePath);
                    break;
                case self::CONTENT_TYPE_XML:
                    $xmlReader = new XmlReader();
                    $parsedContent = $xmlReader->fromFile($filePath);
                    break;
                case self::CONTENT_TYPE_ARRAY:
                    $parsedContent = include $filePath;
                    break;
                case self::CONTENT_TYPE_ENV:
                    $parsedContent = DotenvLoader::loadIntoArray($filePath);
                    break;
            }

            if (\is_array($parsedContent)) {
                return $parsedContent;
            }
            return [];
        } catch (RuntimeException $runtimeException) {
            throw new GeneralException(\sprintf(
                "An error occurred while parsing the given file '%s' and content '%s'. Error message is: '%s'.",
                $filePath,
                $contentType,
                $runtimeException
            ));
        }
    }

    /**
     * Write the given content to the specified file.
     *
     * @param mixed $content The content to be written.
     * @param string $filePath The file to be changed.
     * @param string $contentType The content type (supported types are the
     * constants whose name starts with the prefix 'CONTENT_TYPE').
     *
     * @return bool True if the content was successfully written to the
     * given file path.
     *
     * @throws GeneralException
     */
    public static function writeToFile($content, string $filePath, string $contentType): bool
    {
        try {
            switch ($contentType) {
                case self::CONTENT_TYPE_INI:
                    $iniWriter = new IniWriter();
                    $iniWriter->toFile($filePath, $content);
                    break;
                case self::CONTENT_TYPE_YAML:
                    $ymlContent = YamlReader::dump($content);
                    file_put_contents($filePath, $ymlContent);
                    break;
                case self::CONTENT_TYPE_JSON:
                    $jsonWriter = new JsonWriter();
                    $jsonWriter->toFile($filePath, $content);
                    break;
                case self::CONTENT_TYPE_XML:
                    $xmlWriter = new XmlWriter();
                    $xmlWriter->toFile($filePath, $content);
                    break;
                case self::CONTENT_TYPE_ARRAY:
                    $phpWriter = new PhpArray();
                    $phpWriter->toFile($filePath, $content);
                    break;
                case self::CONTENT_TYPE_ENV:
                    $dotenvWriter = new DotenvWriter();
                    $dotenvWriter->toFile($filePath, $content);
                    break;
            }
        } catch (\Exception $exception) {
            throw new GeneralException(sprintf(
                "It was not possible to save the given content to the specified file '%s'. Error message is: '%s",
                $filePath,
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * Return an array containing all supported content types
     * (class constants with prefix 'CONTENT_TYPE').
     *
     * @return array<mixed, mixed> An array of supported content types.
     */
    public static function getSupportedContentTypes(): array
    {
        return self::getClassConstants('CONTENT_TYPE');
    }

    /**
     * Return a file extension for the given content type. The only content types
     * supported are the class constants starting with "CONTENT_TYPE_".
     *
     * @param string $contentType The file content type (supported content types
     * -> class constants starting "CONTENT_TYPE_" ).
     *
     * @return string The file extension for the given content type (only works with
     * supported content types -> class constants starting "CONTENT_TYPE_").
     *
     * @throws GeneralException Content type not supported.
     */
    public static function getFileExtensionByContentType(string $contentType): string
    {
        switch ($contentType) {
            case self::CONTENT_TYPE_INI:
                return 'ini';
            case self::CONTENT_TYPE_YAML:
                return 'yml';
            case self::CONTENT_TYPE_JSON:
                return 'json';
            case self::CONTENT_TYPE_XML:
                return 'xml';
            case self::CONTENT_TYPE_ARRAY:
                return 'php';
            case self::CONTENT_TYPE_ENV:
                return '';
            default:
                throw new GeneralException('Content type not supported.');
        }
    }

    /**
     * Return a file content type for the given file extension.
     *
     * @param string $extension The file extension.
     *
     * @return string The file content type for the given file extension.
     *
     * @throws GeneralException Content type not supported.
     */
    public static function getContentTypeByFileExtension(string $extension): string
    {
        switch ($extension) {
            case 'ini':
                return self::CONTENT_TYPE_INI;
            case 'yml':
                return self::CONTENT_TYPE_YAML;
            case 'json':
                return self::CONTENT_TYPE_JSON;
            case 'xml':
                return self::CONTENT_TYPE_XML;
            case 'php':
                return self::CONTENT_TYPE_ARRAY;
            case '':
                return self::CONTENT_TYPE_ENV;
            default:
                throw new GeneralException('File extension not supported.');
        }
    }

    /**
     * Append to the given file name the extension for the specified content type.
     *
     * @param string $fileName The initial file name.
     * @param string $contentType The content type.
     *
     * @return string The file name with extension appended.
     *
     * @throws GeneralException
     */
    public static function appendContentTypeExtension(string $fileName, string $contentType): string
    {
        $fileExtension = self::getFileExtensionByContentType($contentType);
        if ($fileExtension) {
            $fileName .= '.' . $fileExtension;
        }

        return $fileName;
    }

    /**
     * Export the given array to the given destination full file path. If no destination
     * full file path is specified, a default path will be generated as follows:
     * - use the $defaultNamePrefix parameter concatenated with the execution timestamp
     *   to generate the destination file name;
     * - use the $exportDirPath parameter to define the export directory; if this parameter
     *   is empty, the execution directory will be used.
     *
     * @param array<mixed, mixed> $content The array to write to the destination file.
     * @param string $contentType The file content type (accepted values are
     * FileUtils constants starting with "CONTENT_TYPE_").
     * @param string $destinationFullFilePath The destination file path. If not given,
     * a default file name will be created.
     * @param string $defaultNamePrefix In case no destination file is specified,
     * this prefix will be used to generate a default file name (this prefix
     * concatenated with the execution timestamp).
     * @param string $exportDirPath In case no destination file is specified,
     * this field will be used to generated the default file name full path;
     * if empty, the execution directory will be used.
     *
     * @return string The export full file path.
     *
     * @throws GeneralException An error occurred while writing the
     * given array content to the export file.
     */
    public static function exportArrayReportToFile(
        array $content,
        string $contentType = self::CONTENT_TYPE_JSON,
        string $destinationFullFilePath = '',
        string $defaultNamePrefix = 'export_data',
        string $exportDirPath = ''
    ): string
    {
        if ('' !== $destinationFullFilePath && is_dir($destinationFullFilePath)) {
            throw new GeneralException('The given destination file path cannot be a directory.');
        }

        if ('' === $destinationFullFilePath) {
            // We check the given parameters
            if ('' !== $exportDirPath) {
                $exportDirPath = rtrim($exportDirPath, DIRECTORY_SEPARATOR);
            } else {
                $exportDirPath = '.';
            }

            // We define a default name
            $fileName = rtrim($defaultNamePrefix, '_') . '_' . number_format(microtime(true), 12, '', '');
            $fileName = self::appendContentTypeExtension($fileName, $contentType);
            $destinationFullFilePath = $exportDirPath . DIRECTORY_SEPARATOR . $fileName;
        }

        // If XML content type, we have to define a parent node name
        if (self::CONTENT_TYPE_XML === $contentType) {
            $content = ['element' => $content];
        }

        // We write the result to the file path
        self::writeToFile($content, $destinationFullFilePath, $contentType);

        return $destinationFullFilePath;
    }
}
