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

use Dotenv\Parser\Lines;
use Dotenv\Parser\Parser;
use Forte\Stdlib\Exceptions\GeneralException;
use PhpOption\Option;

/**
 * Methods to read a .env file and load its content.
 *
 * @package Forte\Stdlib
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class DotenvLoader
{
    /**
     * Loads the content of the specified file into an array and returns it.
     *
     * @param string $filePath The path of the file to be read.
     *
     * @return array An array representation of the given file.
     *
     * @throws GeneralException
     */
    public static function loadIntoArray(string $filePath)
    {
        // We get the given file content as a list of strings
        $content = self::findAndRead($filePath);
//TODO check the preg_split second parameter... should it be a string or an array?
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
        // We need to add double quotes to the value, if it contains spaces
        if (str_contains($value, ' ')) {
            $value = '"' . $value . '"';
        }

        return "$key=$value";
    }

    /**
     * Attempt to read the provided file.
     *
     * @param string $filePath The path of the file to be read.
     *
     * @return array<string>
     *
     * @throws GeneralException Either the file path is empty or it was not
     * possible to read the specified file.
     */
    protected static function findAndRead(string $filePath)
    {
        if ('' !== $filePath) {
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
     * We'll fill out any nested variables, and actually set the variable using
     * the underlying environment variables instance.
     *
     * @param array<string> $entries
     *
     * @return array<string, mixed>
     */
    protected static function processEntries(array $entries): array
    {
        $vars = [];
        foreach ($entries as $entry) {
            $parsedEntries = (new Parser())->parse($entry);
            foreach ($parsedEntries as $parsedEntry) {
                $value = $parsedEntry->getValue();
                if (is_numeric($value)) {
                    $value = is_int($value) ? intval($value) : floatval($value);
                } elseif (is_string($value)) {
                    if ('false' === strtolower($value)) {
                        $value = false;
                    } elseif ('true' === strtolower($value)) {
                        $value = true;
                    }
                }
                $vars[$parsedEntry->getName()] = $value;
            }
        }
        return $vars;
    }
}
