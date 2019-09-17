<?php

namespace Forte\Stdlib;

use Forte\Stdlib\Exceptions\MissingKeyException;

/**
 * Class ArrayUtils. Utility class for handling various array actions.
 *
 * @package Forte\Stdlib
 */
class ArrayUtils
{
    /**
     * The separator used in multi-level array keys.
     *
     * e.g. The multi-level key "level-1.level-2.final-level"
     * corresponds to the following array:
     * [
     *    'level-1' => [
     *        'level-2' => 'final-level',
     *     ],
     * ]
     */
    const ARRAY_KEYS_LEVEL_SEPARATOR = ".";

    /**
     * Filter the given array and returns a sub-array containing only those
     * elements whose keys start with the given prefix.
     *
     * @param array $array An array to filter by key.
     * @param string $prefix The prefix to filter by row key.
     *
     * @return array An array whose keys starts with the given prefix.
     */
    public static function filterArrayByPrefixKey(array $array, string $prefix = ''): array
    {
        return array_filter(
            $array,
            function ($key) use ($prefix) {
                return (strpos($key, $prefix) === 0);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Convert all elements in the given variables list to an array.
     *
     * @param array $variables
     *
     * @return array
     */
    public static function variablesToArray(array $variables): array
    {
        $toArray = [];
        foreach ($variables as $key => $variable) {
            if ($variable instanceof ArrayableInterface) {
                $toArray[$key] = $variable->toArray();
            } elseif (is_array($variable)) {
                $toArray[$key] = self::variablesToArray($variable);
            } else {
                $toArray[$key] = $variable;
            }
        }

        return $toArray;
    }

    /**
     * Return the array value for the given key; if not defined, an error will be thrown.
     *
     * @param string $key The multi-level array key.
     * @param array $array The array to access with the given multi-level key.
     *
     * @return mixed
     *
     * @throws MissingKeyException
     */
//TODO REQUIRED SHOULD BE A FUNCTION PARAMETER
//TODO THE NAME SHOULD BE ALSO DIFFERENT: getValueFromArray extractValueFromArray or extractValue
    public static function getRequiredArrayValue(string $key, array $array)
    {
        $keysTree = explode(self::ARRAY_KEYS_LEVEL_SEPARATOR, $key, 2);
        $value = null;
        if (count($keysTree) <= 2) {
            // We check if a value for the current array key exists;
            // If it does not exist, we throw an error.
            $currentKey = $keysTree[0];
            if (array_key_exists($currentKey, $array)) {
                $value = $array[$currentKey];
            } else {
                throw new MissingKeyException($key, "Array key '$key' not found.");
            }

            try {
                // If a value for the current key was found, we check
                // if we need to iterate again through the given array;
                if (count($keysTree) === 2) {
                    if (is_array($value)) {
                        $value = self::getRequiredArrayValue($keysTree[1], $value);
                    } else {
                        throw new MissingKeyException($keysTree[1], "Array key '$keysTree[1]' not found.");
                    }
                }
            } catch (MissingKeyException $e) {
                $composedKey = $currentKey . self::ARRAY_KEYS_LEVEL_SEPARATOR . $e->getMissingKey();
                throw new MissingKeyException($composedKey, "Array key '$composedKey' not found.");
            }
        }
        return $value;
    }
}