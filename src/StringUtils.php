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
            return strcmp($check, $equalTo) === 0;
        } else {
            return strcasecmp($check, $equalTo) === 0;
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
            return strcmp($check, $lessThan) < 0;
        } else {
            return strcasecmp($check, $lessThan) < 0;
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
            return strcmp($check, $lessThanEqualTo) <= 0;
        } else {
            return strcasecmp($check, $lessThanEqualTo) <= 0;
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
            return strcmp($check, $greaterThan) > 0;
        } else {
            return strcasecmp($check, $greaterThan) > 0;
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
    public static function greaterThanEqualTo(string $check, string $greaterThanEqualTo, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return strcmp($check, $greaterThanEqualTo) >= 0;
        } else {
            return strcasecmp($check, $greaterThanEqualTo) >= 0;
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
            return strcmp($check, $differentThan) !== 0;
        } else {
            return strcasecmp($check, $differentThan) !== 0;
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
            $found = strpos($check, $contains);
        } else {
            $found = stripos($check, $contains);
        }

        return ((!is_bool($found) && $found >= 0) ? true : false);
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

    /**
     * Strip all occurrences of the given string from the end of a string.
     *
     * @param string $str The input string.
     * @param string $remove String to remove.
     *
     * @return string The modified string.
     */
    public static function rightTrim($str, $remove = null)
    {
        $str = (string) $str;
        $remove = (string) $remove;

        if(empty($remove)) {
            return rtrim($str);
        }

        $len = strlen($remove);
        $offset = strlen($str)-$len;
        while($offset > 0 && $offset == strpos($str, $remove, $offset)) {
            $str = substr($str, 0, $offset);
            $offset = strlen($str)-$len;
        }

        return rtrim($str);
    }
}
