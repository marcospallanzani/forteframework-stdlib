<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\ArrayUtils;
use Forte\Stdlib\Exceptions\MissingKeyException;

/**
 * Class ArrayUtilsTest.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class ArrayUtilsTest extends BaseTest
{
    /**
     * A general test array.
     *
     * @var array
     */
    protected $testArray = [
        'test1' => [
            'test2' => 'value2'
        ],
        'test5' => [
            'test6' => [
                'test7' => [
                    'test8' => [
                        'test9' => [
                            'test10' => 'value10'
                        ]
                    ]
                ],
            ]
        ],
    ];

    /**
     * Data provider for filter-by-prefix-key tests.
     *
     * @return array
     */
    public function arraysProvider(): array
    {
        $filterArray =  [
            "FILTER_0" => "FILTER_0_VALUE",
            "FILTER_1" => "FILTER_1_VALUE",
            "FILTER_2" => "FILTER_2_VALUE",
        ];

        $filterByStringArray =  [
            "FILTER_BY_STRING_0" => "FILTER_BY_STRING_0_VALUE",
            "FILTER_BY_STRING_1" => "FILTER_BY_STRING_1_VALUE",
            "FILTER_BY_STRING_2" => "FILTER_BY_STRING_2_VALUE",
        ];

        $fullArray = array_merge($filterArray, $filterByStringArray);

        return [
            [$fullArray, "FILTER", $fullArray],
            [$fullArray, "FILTER_0", ["FILTER_0" => "FILTER_0_VALUE"]],
            [$fullArray, "FILTER_BY", $filterByStringArray],
            [$fullArray, "FILTER_BY_STRING", $filterByStringArray],
            [$fullArray, "FILTER_BY_STRING_0", ["FILTER_BY_STRING_0" => "FILTER_BY_STRING_0_VALUE"]],
        ];
    }


    /**
     * Data provider for all config access tests.
     *
     * @return array
     */
    public function configProvider(): array
    {
        return [
            //  access key | content to be checked | expected value for the given key | an exception is expected
            ['test1', $this->testArray, ['test2' => 'value2'], false],
            ['WRONG-KEY', $this->testArray, ['test2' => 'value2'], true],
            ['test1.test2', $this->testArray, 'value2', false],
            ['test1.WRONG-KEY', $this->testArray, 'value2', true],
            ['test5.test6.test7.test8.test9', $this->testArray, ['test10' => 'value10'], false],
            ['test5.test6.test7.WRONG-KEY.test9', $this->testArray, ['test10' => 'value10'], true],
        ];
    }

    /**
     * Test method ArrayUtils::filterArrayByPrefixKey().
     *
     * @dataProvider arraysProvider
     *
     * @param array $initialArray
     * @param string $prefix
     * @param array $expected
     */
    public function testFilterArrayByPrefixKey(array $initialArray, string $prefix, array $expected): void
    {
        $this->assertEquals($expected, ArrayUtils::filterArrayByPrefixKey($initialArray, $prefix));
    }

    /**
     * Test method ArrayUtils::variablesToArray().
     */
    public function testVariablesToArray(): void
    {
        $anonymousClass      = $this->getAnonymousClass();
        $childAnonymousClass = $this->getAnonymousClass();

        // We build the expected output
        $arrayAnonymousAction = $anonymousClass->toArray();
        $anonymousClass->addArrayableObject($childAnonymousClass);
        $arrayAnonymousAction['objects'][] = $childAnonymousClass->toArray();

        $this->assertEquals(
            ['key' => 'value1', 'value', true, $arrayAnonymousAction],
            ArrayUtils::variablesToArray(['key' => 'value1', 'value', true, $anonymousClass])
        );
    }

    /**
     * Test the method ArrayUtils::getRequiredNestedConfigValue().
     *
     * @dataProvider configProvider
     *
     * @param string $key
     * @param array $checkContent
     * @param mixed $expectedValue
     * @param bool $expectException
     *
     * @throws MissingKeyException
     */
    public function testRequiredNestedConfigValue(
        string $key,
        array $checkContent,
        $expectedValue,
        bool $expectException
    ): void
    {
        if ($expectException) {
            $this->expectException(MissingKeyException::class);
        }
        $this->assertEquals($expectedValue, ArrayUtils::getRequiredArrayValue($key, $checkContent));
    }
}
