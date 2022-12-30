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

use Forte\Stdlib\Exceptions\MissingKeyException;

/**
 * Utility class for handling different array actions.
 *
 * @package Forte\Stdlib
 * @author  Marco Spallanzani <forteframework@gmail.com>
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
    public const ARRAY_KEYS_LEVEL_SEPARATOR = '.';

    /**
     * Filter the given array and returns a sub-array containing only those
     * elements whose keys start with the given prefix.
     *
     * @param array<mixed, mixed> $array An array to filter by key.
     * @param string $prefix The prefix to filter by row key.
     *
     * @return array<mixed, mixed> An array whose keys starts with the given prefix.
     */
    public static function filterArrayByPrefixKey(array $array, string $prefix = ''): array
    {
        return \array_filter(
            $array,
            function ($key) use ($prefix) {
                return \str_starts_with((string) $key, $prefix);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Convert all elements in the given variables list to an array.
     *
     * @param array<mixed, mixed> $variables
     *
     * @return array<mixed, mixed>
     */
    public static function variablesToArray(array $variables): array
    {
        $toArray = [];
        foreach ($variables as $key => $variable) {
            if ($variable instanceof ArrayableInterface) {
                $toArray[$key] = $variable->toArray();
            } elseif (\is_array($variable)) {
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
     * @param array<mixed, mixed> $array The array to access with the given multi-level key.
     *
     * @return mixed
     *
     * @throws MissingKeyException
     */
    public static function getValueFromArray(string $key, array $array)
    {
        $keysTree = explode(self::ARRAY_KEYS_LEVEL_SEPARATOR, $key, 2);
        $value = null;
        if (\count($keysTree) <= 2) {
            // We check if a value for the current array key exists;
            // If it does not exist, we throw an error.
            $currentKey = $keysTree[0];
            if (\array_key_exists($currentKey, $array)) {
                $value = $array[$currentKey];
            } else {
                throw new MissingKeyException($key, "Array key '$key' not found.");
            }

            try {
                // If a value for the current key was found, we check
                // if we need to iterate again through the given array;
                if (2 === \count($keysTree)) {
                    if (\is_array($value)) {
                        $value = self::getValueFromArray($keysTree[1], $value);
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
