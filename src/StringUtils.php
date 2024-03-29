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
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word;

/**
 * Utility class for handling various string actions.
 *
 * @package Forte\Stdlib
 */
class StringUtils
{
    /**
     * Check if the given check string is equal to the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $equalTo The expected equal-to string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string is equal to the given
     * search string; false otherwise.
     */
    public static function equalTo(string $check, string $equalTo, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return 0 === \strcmp($check, $equalTo);
        } else {
            return 0 === \strcasecmp($check, $equalTo);
        }
    }

    /**
     * Check if the given check string is less than the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $lessThan The expected less-than string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string is less than the given
     * search string; false otherwise.
     */
    public static function lessThan(string $check, string $lessThan, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return \strcmp($check, $lessThan) < 0;
        } else {
            return \strcasecmp($check, $lessThan) < 0;
        }
    }

    /**
     * Check if the given check string is less than or equal to the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $lessThanEqualTo The expected less-than/equal-to string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string is less than or equal to the given
     * search string; false otherwise.
     */
    public static function lessThanEqualTo(string $check, string $lessThanEqualTo, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return \strcmp($check, $lessThanEqualTo) <= 0;
        } else {
            return \strcasecmp($check, $lessThanEqualTo) <= 0;
        }
    }

    /**
     * Check if the given check string is greater than the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $greaterThan The expected greater-than string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string is greater than the given
     * search string; false otherwise.
     */
    public static function greaterThan(string $check, string $greaterThan, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return \strcmp($check, $greaterThan) > 0;
        } else {
            return \strcasecmp($check, $greaterThan) > 0;
        }
    }

    /**
     * Check if the given check string is greater than or equal to the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $greaterThanEqualTo The expected greater-than/equal-to string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string is greater than or equal to the given
     * search string; false otherwise.
     */
    public static function greaterThanEqualTo(
        string $check,
        string $greaterThanEqualTo,
        bool $caseSensitive = false
    ): bool
    {
        if ($caseSensitive) {
            return \strcmp($check, $greaterThanEqualTo) >= 0;
        } else {
            return \strcasecmp($check, $greaterThanEqualTo) >= 0;
        }
    }

    /**
     * Check if the given check string is different than the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $differentThan The expected different-than string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string is different than the given
     * search string; false otherwise.
     */
    public static function differentThan(string $check, string $differentThan, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return 0 !== \strcmp($check, $differentThan);
        } else {
            return 0 !== \strcasecmp($check, $differentThan);
        }
    }

    /**
     * Check if the given check string contains the given 'contain' string.
     *
     * @param string $check The string to be checked.
     * @param string $contains The expected "contain" string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string contains the given 'contain' string; false otherwise.
     */
    public static function contains(string $check, string $contains, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            $found = \strpos($check, $contains);
        } else {
            $found = \stripos($check, $contains);
        }

        return !\is_bool($found) && $found >= 0;
    }

    /**
     * Check if the given check string starts with the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $startsWith The expected starts-with string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string starts with the given
     * search string; false otherwise.
     */
    public static function startsWith(string $check, string $startsWith, bool $caseSensitive = false): bool
    {
        $length = \strlen($startsWith);
        $subString = \substr($check, 0, $length);
        if ($caseSensitive) {
            return 0 === \strcmp($subString, $startsWith);
        } else {
            return 0 === \strcasecmp($subString, $startsWith);
        }
    }

    /**
     * Check if the given check string ends with the given search string.
     *
     * @param string $check The string to be checked.
     * @param string $endsWith The expected ends-with string.
     * @param bool $caseSensitive Whether the check should be case sensitive or not.
     *
     * @return bool True if the given check string ends with the given
     * search string; false otherwise.
     */
    public static function endsWith(string $check, string $endsWith, bool $caseSensitive = false): bool
    {
        $length = \strlen($endsWith);
        if (0 === $length) {
            return true;
        }
        $subString = \substr($check, -$length);
        if ($caseSensitive) {
            return 0 === \strcmp($subString, $endsWith);
        } else {
            return 0 === \strcasecmp($subString, $endsWith);
        }
    }

    /**
     * Return a string version of the given variable (arrays are converted to a json string).
     *
     * @param mixed $variable The variable to be converted to a string.
     *
     * @return string String representation of the given variable
     *
     * @throws \JsonException An error occurred while converting an array to a string.
     * @throws GeneralException The given variable is of an unsupported type.
     */
    public static function stringifyVariable(mixed $variable): string
    {
        if (\is_array($variable)) {
            return \json_encode($variable, JSON_THROW_ON_ERROR);
        } elseif (\is_object($variable)) {
            return \sprintf(
                'Class type: %s. Object value: %s.',
                \get_class((object) $variable),
                self::stringifyVariable(\get_object_vars($variable))
            );
        } elseif (\is_bool($variable)) {
            return ($variable ? 'true' : 'false');
        } elseif (\is_null($variable)) {
            return 'null';
        } elseif (\is_scalar($variable)) {
            return \strval($variable);
        }

        throw new GeneralException('Impossible to convert the given value to a string');
    }

    /**
     * This function replaces the given parameters in the given message.
     *
     * @param string $message The message to be formatted.
     * @param array<mixed, mixed> $parameters The values to replace in the given message.
     *
     * @return string The formatted string.
     */
    public static function getFormattedMessage(string $message, ...$parameters): string
    {
        return \vsprintf($message, $parameters);
    }

    /**
     * Return a random unique id (at least 64 characters).
     *
     * @param string $prefix A string to prepend to the random unique id.
     *
     * @return string A random unique id.
     */
    public static function getRandomUniqueId(string $prefix = ''): string
    {
        return
            ('' !== $prefix ? $prefix . '_' : '') .
            \sha1((string) \rand()) . '_' .
            \number_format(\microtime(true), 12, '', '');
    }

    /**
     * Strip all occurrences of the given string from the end of a string.
     *
     * @param string $string The input string.
     * @param string|null $remove String to remove.
     *
     * @return string The modified string.
     */
    public static function rightTrim(string $string, ?string $remove = null): string
    {
        $remove = (string) $remove;
        if ('' === $remove) {
            return \rtrim($string);
        }

        $length = \strlen($remove);
        $offset = \strlen($string) - $length;
        while ($offset > 0 && $offset === \strpos($string, $remove, $offset)) {
            $string = \substr($string, 0, $offset);
            $offset = \strlen($string) - $length;
        }

        return \rtrim($string);
    }

    /**
     * Strip all occurrences of the given string from the beginning of a string.
     *
     * @param string $string The input string.
     * @param string|null $remove String to remove.
     *
     * @return string The modified string.
     */
    public static function leftTrim(string $string, ?string $remove = null): string
    {
        $remove = (string) $remove;
        if ('' === $remove) {
            return \rtrim($string);
        }

        $length = \strlen($remove);
        while (\str_starts_with($string, $remove)) {
            $string = \substr($string, $length, \strlen($string));
        }

        return \rtrim($string);
    }

    /**
     * Strip all occurrences of the given string from the beginning and the end of a string.
     *
     * @param string $string The input string.
     * @param string|null $remove String to remove.
     *
     * @return string The modified string.
     */
    public static function trim(string $string, ?string $remove = null): string
    {
        return self::rightTrim(
            self::leftTrim($string, $remove),
            $remove
        );
    }

    /**
     * Normalize the given value. The following changes are applied:
     * - convert all camel cases to a chain of words separated by empty spaces (" ");
     * - convert all dashes ("-") to empty spaces (" ");
     * - convert all underscores ("_") to empty spaces (" ");
     * - convert all upper cases to lower cases;
     * - if a separator is specified, converts all spaces to the given separator (action applied at the end).
     *
     * e.g. "MyApp built_with forte-framework" -> "my app built with forte framework" (no separator specified).
     * e.g. "MyApp built_with forte-framework" -> "my-app-built-with-forte-framework" (separator = "-").
     * e.g. "MyApp built_with forte-framework" -> "my_app_built_with_forte_framework" (separator = "_").
     *
     * @param string $value The value to be normalized.
     * @param string $separator The desired final separator, if specified.
     *
     * @return string A normalized string representation of the given string value.
     */
    public static function getNormalizedString(string $value, string $separator = ''): string
    {
        if ($value) {
            /**
             * We first convert camel cases to spaces.
             * e.g. "MyForteAPI Test_under Test-dashes" -> "My Forte API Test Test_under Test-dashes"
             */
            $normalizedValue = (new Word\CamelCaseToSeparator())->filter($value);
            /**
             * We convert dashes to empty spaces
             * e.g. "My Forte API Test Test_under Test-dashes" -> "My Forte API Test Test_under Test dashes"
             */
            $normalizedValue = (new Word\DashToSeparator())->filter($normalizedValue);
            /**
             * We convert underscores to empty spaces.
             * e.g. "My Forte API Test Test_under Test dashes" -> "My Forte API Test Test under Test dashes"
             */
            $normalizedValue = (new Word\UnderscoreToSeparator())->filter($normalizedValue);

            if ($separator) {
                /**
                 * Now we are sure that we have a non-camel string without dashes or underscores:
                 * we can replace all the spaces with the given desired separator.
                 */
                $normalizedValue = (new Word\SeparatorToSeparator(' ', $separator))->filter($normalizedValue);
            }

            return \strval((new StringToLower())->filter($normalizedValue));
        }
        return '';
    }
}
