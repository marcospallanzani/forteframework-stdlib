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

use Dotenv\Exception\InvalidFileException;
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
     * @return array<string, mixed> An array representation of the given file.
     *
     * @throws GeneralException
     */
    public static function loadIntoArray(string $filePath): array
    {
        // We get the given file content as a list of strings
        $content = self::findAndRead($filePath);
        try {
            // We remove the empty spaces and comments, and we return a list
            // of all env variables found in the file
            $lines = [];

            $contentLines = \preg_split("/(\r\n|\n|\r)/", $content);
            if (true === \is_array($contentLines)) {
                foreach ($contentLines as $line) {
                    if (true === \is_string($line)) {
                        $lines[] = $line;
                    }
                }
            }
            return self::processEntries(Lines::process($lines));
        } catch (InvalidFileException $invalidFileException) {
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
        if (\str_contains($value, ' ')) {
            $value = '"' . $value . '"';
        }

        return "$key=$value";
    }

    /**
     * Attempt to read the provided file.
     *
     * @param string $filePath The path of the file to be read.
     *
     * @return string The file content
     *
     * @throws GeneralException Either the file path is empty or it was not
     * possible to read the specified file.
     */
    protected static function findAndRead(string $filePath): string
    {
        if ('' === \trim($filePath)) {
            throw new GeneralException('The file path must be provided.');
        }

        $content = @\file_get_contents($filePath);
        $lines = Option::fromValue($content, false);

        if ($lines->isDefined()) {
            return (string) $lines->get();
        }

        throw new GeneralException(
            \sprintf('Unable to read the environment file [%s].', $filePath)
        );
    }

    /**
     * Process the environment variable entries.
     *
     * @param array<string> $entries
     *
     * @return array<string, mixed>
     */
    protected static function processEntries(array $entries): array
    {
        $vars = [];
        foreach ($entries as $entry) {
            $parserEntries = (new Parser())->parse($entry);
            foreach ($parserEntries as $parserEntry) {
                $value = $parserEntry->getValue();
                if ($value instanceof \PhpOption\Some) {
                    $value = $value->get();
                    if ($value instanceof \Dotenv\Parser\Value) {
                        $value = $value->getChars();
                    }
                }

                if (\is_numeric($value)) {
                    $value = \is_int($value) ? $value : \floatval($value);
                } elseif (\is_string($value)) {
                    if ('false' === \strtolower($value)) {
                        $value = false;
                    } elseif ('true' === \strtolower($value)) {
                        $value = true;
                    }
                }
                $vars[$parserEntry->getName()] = $value;
            }
        }
        return $vars;
    }
}
