<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\StringUtils;

/**
 * Class StringUtilsTest.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class StringUtilsTest extends BaseTest
{
    /**
     * Data provider for stringify tests.
     *
     * @return array
     */
    public function stringifyProvider(): array
    {
        $stdClass = new \stdClass();
        $stdClass->test1 = "value1";

        return [
            [null, 'null'],
            [false, 'false'],
            [true, 'true'],
            ['regular string', 'regular string'],
            [100, '100'],
            ['100', '100'],
            [100.10, '100.1'],
            [['test1' => 'value1'], '{"test1":"value1"}'],
            [$stdClass, 'Class type: stdClass. Object value: {"test1":"value1"}.'],
        ];
    }

    /**
     * Test the StringUtils::startsWith() method.
     */
    public function testStartsWith(): void
    {
        $this->assertTrue(StringUtils::startsWith("This is a test", "This is"));
        $this->assertFalse(StringUtils::startsWith("This is a test", "Another string"));
        $this->assertFalse(StringUtils::startsWith("This is a test", "Another longer string"));
    }

    /**
     * Test the StringUtils::endsWith() method.
     */
    public function testEndsWith(): void
    {
        $this->assertTrue(StringUtils::endsWith("This is a test", ""));
        $this->assertTrue(StringUtils::endsWith("This is a test", "test"));
        $this->assertFalse(StringUtils::endsWith("This is a test", "Another string"));
        $this->assertFalse(StringUtils::endsWith("This is a test", "Another longer string"));
    }

    /**
     * Test the StringUtils::stringifyVariable() method.
     *
     * @dataProvider stringifyProvider
     *
     * @param mixed $variable
     * @param string $expected
     */
    public function testStringifyVariable($variable, string $expected): void
    {
        $this->assertEquals($expected, StringUtils::stringifyVariable($variable));
    }

    /**
     * Test the StringUtils::getFormattedMessage() method.
     */
    public function testFormatMessage(): void
    {
        $this->assertEquals('test formatted string', StringUtils::getFormattedMessage('test %s %s', 'formatted', 'string'));
        $this->assertEquals('test formatted 10', StringUtils::getFormattedMessage('test %s %d', 'formatted', 10.01));
        $this->assertStringStartsWith('test formatted 10.01', StringUtils::getFormattedMessage('test %s %f', 'formatted', 10.01));
    }

    /**
     * Test the StringUtils::getRandomUniqueId() method.
     */
    public function testGetRandomUniqueId(): void
    {
        $prefix = "TestWithPrefix";
        $randomId = StringUtils::getRandomUniqueId($prefix);
        $this->assertStringStartsWith($prefix, $randomId);
        $this->assertEquals(strlen($prefix) + 64, strlen($randomId));
    }
}