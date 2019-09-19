<?php

namespace Forte\Stdlib;

/**
 * Class StringUtils.
 *
 * Utility class for handling various string actions.
 *
 * @package Forte\Stdlib
 */
class StringUtils
{
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
        $length = strlen($startsWith);
        $subString = substr($check, 0, $length);
        if ($caseSensitive) {
            return strcmp($subString, $startsWith) === 0;
        } else {
            return strcasecmp($subString, $startsWith) === 0;
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
        $length = strlen($endsWith);
        if ($length == 0) {
            return true;
        }
        $subString = substr($check, -$length);
        if ($caseSensitive) {
            return strcmp($subString, $endsWith) === 0;
        } else {
            return strcasecmp($subString, $endsWith) === 0;
        }
    }

    /**
     * Return a string version of the given variable (arrays are converted to a json string).
     *
     * @param mixed $variable The variable to be converted to a string.
     *
     * @return string String representation of the given variable
     */
    public static function stringifyVariable($variable): string
    {
        if (is_array($variable)) {
            return json_encode($variable);
        } elseif (is_object($variable)) {
            return sprintf(
                "Class type: %s. Object value: %s.",
                get_class($variable),
                self::stringifyVariable(get_object_vars($variable))
            );
        } elseif (is_bool($variable)) {
            return (boolval($variable) ? 'true' : 'false');
        } elseif (is_null($variable)) {
            return "null";
        } else {
            return (string) $variable;
        }
    }

    /**
     * Return a formatted message.
     *
     * @param string $message The message to be formatted.
     * @param mixed[] $parameters The values to replace in the given message.
     *
     * @return string
     */
    public static function getFormattedMessage(string $message, ...$parameters): string
    {
        return vsprintf($message, $parameters);
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
            (!empty($prefix) ? $prefix . "_" : "") .
            sha1(rand()) . "_" .
            number_format(microtime(true), 12, '', '');
    }
}
