<?php

namespace Forte\Stdlib;

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Writers\Dotenv as DotenvWriter;
use Symfony\Component\Yaml\Yaml as YamlReader;
use Zend\Config\Exception\RuntimeException;
use Zend\Config\Reader\Ini as IniReader;
use Zend\Config\Reader\Json as JsonReader;
use Zend\Config\Reader\Xml as XmlReader;
use Zend\Config\Writer\Ini as IniWriter;
use Zend\Config\Writer\Json as JsonWriter;
use Zend\Config\Writer\PhpArray;
use Zend\Config\Writer\Xml as XmlWriter;

/**
 * Class FileUtils. Utility class for handling various file actions.
 *
 * @package Forte\Stdlib
 */
class FileUtils
{
    use ClassAccessTrait;

    /**
     * Supported content types.
     */
    const CONTENT_TYPE_JSON  = "content_json";
    const CONTENT_TYPE_INI   = "content_ini";
    const CONTENT_TYPE_YAML  = "content_yaml";
    const CONTENT_TYPE_XML   = "content_xml";
    const CONTENT_TYPE_ARRAY = "content_array";
    const CONTENT_TYPE_ENV   = "content_env";

    /**
     * Parse the given file path and return its content as an array.
     *
     * @param string $filePath The file to be parsed.
     * @param string $contentType The content type (supported types are the
     * constants whose name starts with the prefix 'CONTENT_TYPE').
     *
     * @return array An array representing the given file path.
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
                    $parsedContent = include ($filePath);
                    break;
                case self::CONTENT_TYPE_ENV:
                    $parsedContent = DotenvLoader::loadIntoArray($filePath);
                    break;
            }

            if (is_array($parsedContent)) {
                return $parsedContent;
            }
            return [];
        } catch (RuntimeException $runtimeException) {
            throw new GeneralException(sprintf(
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
     * @return array An array of supported content types.
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
                return "ini";
            case self::CONTENT_TYPE_YAML:
                return "yml";
            case self::CONTENT_TYPE_JSON:
                return "json";
            case self::CONTENT_TYPE_XML:
                return "xml";
            case self::CONTENT_TYPE_ARRAY:
                return "php";
            case self::CONTENT_TYPE_ENV:
                return "";
            default:
                throw new GeneralException("Content type not supported.");
        }
    }

    /**
     * Export the given array to the given destination full file path. If no destination
     * full file path is specified, a default path will be generated as follows:
     * - use the $defaultNamePrefix parameter concatenated with the execution timestamp
     *   to generate the destination file name;
     * - use the $exportDirPath parameter to define the export directory; if this parameter
     *   is empty, the execution directory will be used.
     *
     * @param array $content The array to write to the destination file.
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
        string $destinationFullFilePath = "",
        string $defaultNamePrefix = "export_data",
        string $exportDirPath = ""
    ): string
    {
        if (!empty($destinationFullFilePath) && is_dir($destinationFullFilePath)) {
            throw new GeneralException("The given destination file path cannot be a directory.");
        }

        if (empty($destinationFullFilePath)) {
            // We check the given parameters
            if (!empty($exportDirPath)) {
                $exportDirPath = rtrim($exportDirPath, DIRECTORY_SEPARATOR);
            } else {
                $exportDirPath = ".";
            }

            // We define a default name
            $fileName = rtrim($defaultNamePrefix, "_") . "_" . number_format(microtime(true), 12, '', '');
            $fileExtension = self::getFileExtensionByContentType($contentType);
            if ($fileExtension) {
                $fileName .= '.' . $fileExtension;
            }
            $destinationFullFilePath = $exportDirPath . DIRECTORY_SEPARATOR . $fileName;
        }

        // If XML content type, we have to define a parent node name
        if ($contentType === self::CONTENT_TYPE_XML) {
            $content = ['element' => $content];
        }

        // We write the result to the file path
        self::writeToFile($content, $destinationFullFilePath, $contentType);

        return $destinationFullFilePath;
    }
}
