<?php


namespace Forte\Stdlib;

use Dotenv\Lines;
use Dotenv\Parser;
use Forte\Stdlib\Exceptions\GeneralException;
use PhpOption\Option;

/**
 * Class DotenvLoader.
 *
 * @package Forte\Stdlib
 */
class DotenvLoader
{
    /**
     * @param string $filePath
     *
     * @return array
     *
     * @throws GeneralException
     */
    public static function loadIntoArray(string $filePath)
    {
        // We get the given file content as a list of strings
        $content = self::findAndRead($filePath);

        try {
            // We remove the empty spaces and comments and we return a list
            // of all env variables found in the file
            return self::processEntries(
                Lines::process(preg_split("/(\r\n|\n|\r)/", $content))
            );
        } catch (\Dotenv\Exception\InvalidFileException $invalidFileException) {
            throw new GeneralException(sprintf(
                "Error occurred while reading the file '%s'. Error message is: %s",
                $filePath,
                $invalidFileException->getMessage()
            ));
        }
    }

    /**
     * Return a formatted env line for the given key and variable.
     *
     * @param string $key The variable key.
     * @param string $value The variable value.
     *
     * @return string A formatted env line for the given key and variable.
     */
    public static function getLineFromVariables(string $key, string $value): string
    {
        return "$key=$value";
    }

    /**
     * Attempt to read the provided file.
     *
     * @param string $filePath
     *
     * @return string[]
     *
     * @throws GeneralException
     */
    protected static function findAndRead(string $filePath)
    {
        if (empty($filePath)) {
            throw new GeneralException('The file path must be provided.');
        }

        $content = @file_get_contents($filePath);
        $lines = Option::fromValue($content, false);
        if ($lines->isDefined()) {
            return $lines->get();
        }

        throw new GeneralException(
            sprintf('Unable to read the environment file [%s].', $filePath)
        );
    }

    /**
     * Process the environment variable entries.
     *
     * We'll fill out any nested variables, and acually set the variable using
     * the underlying environment variables instance.
     *
     * @param string[] $entries
     *
     * @throws \Dotenv\Exception\InvalidFileException
     *
     * @return array<string|null>
     */
    protected static function processEntries(array $entries)
    {
        $vars = [];
        foreach ($entries as $entry) {
            list($name, $value) = Parser::parse($entry);
            if (is_numeric($value)) {
                $value = ($value == (int) $value) ? (int) $value : (float) $value;
            } elseif (is_string($value)) {
                if (strtolower($value) === "false") {
                    $value = false;
                } elseif (strtolower($value) === "true") {
                    $value = true;
                }
            }

            $vars[$name] = $value;
        }
        return $vars;
    }
}
