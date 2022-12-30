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

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\ClassAccessTrait;

/**
 * @package Forte\Stdlib\Tests\Unit
 */
class ClassAccessTraitTest extends BaseTest
{
    /**
     * Returns an anonymous instance to test the access trait.
     *
     * @return object
     */
    protected function getAnonymousTestClass()
    {
        return new class {
            use ClassAccessTrait;

            public const TEST_PREFIX_0_0 = 0;
            public const TEST_PREFIX_0_1 = 1;
            public const TEST_PREFIX_0_2 = 2;
            public const TEST_PREFIX_0_3 = 3;
            public const TEST_PREFIX_0_4 = 4;
            public const TEST_PREFIX_1_0 = 5;
            public const TEST_PREFIX_1_1 = 6;
            public const TEST_PREFIX_1_2 = 7;
            public const TEST_PREFIX_1_3 = 8;
            public const TEST_PREFIX_1_4 = 9;

            public static int $TEST_PREFIX_A_0 = 0;

            public static int $TEST_PREFIX_A_1 = 1;

            public static int $TEST_PREFIX_A_2 = 2;

            public static int $TEST_PREFIX_A_3 = 3;

            public static int $TEST_PREFIX_A_4 = 4;

            public static int $TEST_PREFIX_B_0 = 5;

            public static int $TEST_PREFIX_B_1 = 6;

            public static int $TEST_PREFIX_B_2 = 7;

            public static int $TEST_PREFIX_B_3 = 8;

            public static int $TEST_PREFIX_B_4 = 9;
        };
    }

    /**
     * Data provider for constants tests.
     *
     * @return array
     */
    public function constantsProvider(): array
    {
        return [
            [
                'TEST_PREFIX_0',
                5,
                ['TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4'],
                ['TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4'],
            ],
            [
                'TEST_PREFIX_1',
                5,
                ['TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4'],
                ['TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4'],
            ],
            [
                'TEST_PREFIX_',
                10,
                [
                    'TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4',
                    'TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4',
                ],
                [],
            ],
            [
                'TEST_PREFIX_NOT_DEFINED',
                0,
                [],
                [
                    'TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4',
                    'TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4',
                ],
            ],
            [
                '',
                10,
                [
                    'TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4',
                    'TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4',
                ],
                [],
            ],
            [
                '',
                10,
                [
                    'TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4',
                    'TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4',
                ],
                [
                    'TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4',
                    'TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4',
                ],
            ],
        ];
    }

    /**
     * Data provider for properties tests.
     *
     * @return array
     */
    public function propertiesProvider(): array
    {
        return [
            [
                'TEST_PREFIX_A',
                5,
                ['TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4'],
                ['TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4'],
            ],
            [
                'TEST_PREFIX_B',
                5,
                ['TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4'],
                ['TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4'],
            ],
            [
                'TEST_PREFIX_',
                10,
                [
                    'TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4',
                    'TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4',
                ],
                [],
            ],
            [
                'TEST_PREFIX_NOT_DEFINED',
                0,
                [],
                [
                    'TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4',
                    'TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4',
                ],
            ],
            [
                '',
                10,
                [
                    'TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4',
                    'TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4',
                ],
                [],
            ],
            [
                '',
                10,
                [
                    'TEST_PREFIX_A_0', 'TEST_PREFIX_A_1', 'TEST_PREFIX_A_2', 'TEST_PREFIX_A_3', 'TEST_PREFIX_A_4',
                    'TEST_PREFIX_B_0', 'TEST_PREFIX_B_1', 'TEST_PREFIX_B_2', 'TEST_PREFIX_B_3', 'TEST_PREFIX_B_4',
                ],
                [
                    'TEST_PREFIX_0_0', 'TEST_PREFIX_0_1', 'TEST_PREFIX_0_2', 'TEST_PREFIX_0_3', 'TEST_PREFIX_0_4',
                    'TEST_PREFIX_1_0', 'TEST_PREFIX_1_1', 'TEST_PREFIX_1_2', 'TEST_PREFIX_1_3', 'TEST_PREFIX_1_4',
                ],
            ],
        ];
    }

    /**
     * @dataProvider constantsProvider
     *
     * @param string $prefix The prefix of the expected class constants.
     * @param int $expectedCount The expected count of class constants with the given prefix.
     * @param array $expectedConstantsKeys The expected class constants.
     * @param array $nonExpectedConstantsKeys The non-expected class constants.
     */
    public function testClassConstants(
        string $prefix,
        int $expectedCount,
        array $expectedConstantsKeys,
        array $nonExpectedConstantsKeys
    ): void
    {
        $class = $this->getAnonymousTestClass();
        $constants = $class::getClassConstants($prefix);
        $this->assertEntries($constants, $expectedCount, $expectedConstantsKeys, $nonExpectedConstantsKeys);
    }

    /**
     * @dataProvider propertiesProvider
     *
     * @param string $prefix The prefix of the expected class static properties.
     * @param int $expectedCount The expected count of properties with the given prefix.
     * @param array $expectedPropertiesKeys The expected static properties.
     * @param array $nonExpectedPropertiesKeys The non-expected static properties.
     */
    public function testClassStaticProperties(
        string $prefix,
        int $expectedCount,
        array $expectedPropertiesKeys,
        array $nonExpectedPropertiesKeys
    ): void
    {
        $class = $this->getAnonymousTestClass();
        $constants = $class::getClassStaticProperties($prefix);
        $this->assertEntries($constants, $expectedCount, $expectedPropertiesKeys, $nonExpectedPropertiesKeys);
    }

    /**
     * Checks if the given entries respect the given conditions (count, expected keys).
     *
     * @param array $entries The entries to be checked.
     * @param int $expectedCount The expected count of entries.
     * @param array $expectedKeys The expected keys in the given entries.
     * @param array $nonExpectedKeys The non-expected keys in the given entries.
     */
    protected function assertEntries(
        array $entries,
        int $expectedCount,
        array $expectedKeys,
        array $nonExpectedKeys
    ): void
    {
        $this->assertIsArray($entries);
        $this->assertCount($expectedCount, $entries);
        foreach ($expectedKeys as $expectedKey) {
            $this->assertArrayHasKey($expectedKey, $entries);
        }
        foreach ($nonExpectedKeys as $nonExpectedKey) {
            $this->assertArrayNotHasKey($nonExpectedKey, $entries);
        }
    }
}
