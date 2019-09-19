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
     * Test the StringUtils::equalTo() method.
     */
    public function testEqualTo(): void
    {
        $this->assertTrue(StringUtils::equalTo("This is a test", "This is a test", false));
        $this->assertTrue(StringUtils::equalTo("This is a test", "This is a test", true));
        $this->assertTrue(StringUtils::equalTo("This is a test", "This is a TeSt", false));
        $this->assertFalse(StringUtils::equalTo("This is a test", "This is a TeSt", true));
        $this->assertFalse(StringUtils::equalTo("This is a test", "Another longer string", false));
        $this->assertFalse(StringUtils::equalTo("This is a test", "Another longer string", true));
    }

    /**
     * Test the StringUtils::lessThan() method.
     */
    public function testLessThan(): void
    {
        $stringContent1 = "This is a test";
        $stringContent2 = "This is a TEST";
        $numericContent1 = "1.01";
        $numericContent2 = "1.02";

        $this->assertTrue(StringUtils::lessThan($numericContent1, $numericContent2, false));
        $this->assertTrue(StringUtils::lessThan($numericContent1, $numericContent2, true));
        $this->assertFalse(StringUtils::lessThan($stringContent2, $stringContent1, false));
        $this->assertTrue(StringUtils::lessThan($stringContent2, $stringContent1, true));
    }

    /**
     * Test the StringUtils::lessThanEqualTo() method.
     */
    public function testLessThanEqualTo(): void
    {
        $stringContent1 = "This is a test";
        $stringContent2 = "This is a TEST";
        $numericContent1 = "1.01";
        $numericContent2 = "1.02";

        $this->assertTrue(StringUtils::lessThanEqualTo($numericContent1, $numericContent2, false));
        $this->assertTrue(StringUtils::lessThanEqualTo($numericContent1, $numericContent1, false));
        $this->assertTrue(StringUtils::lessThanEqualTo($numericContent1, $numericContent2, true));
        $this->assertTrue(StringUtils::lessThanEqualTo($numericContent1, $numericContent1, true));
        $this->assertTrue(StringUtils::lessThanEqualTo($stringContent2, $stringContent1, false));
        $this->assertTrue(StringUtils::lessThanEqualTo($stringContent2, $stringContent1, true));
    }

    /**
     * Test the StringUtils::greaterThan() method.
     */
    public function testGreaterThan(): void
    {
        $stringContent1 = "This is a test";
        $stringContent2 = "This is a TEST";
        $numericContent1 = "1.01";
        $numericContent2 = "1.02";

        $this->assertTrue(StringUtils::greaterThan($numericContent2, $numericContent1, false));
        $this->assertTrue(StringUtils::greaterThan($numericContent2, $numericContent1, true));
        $this->assertFalse(StringUtils::greaterThan($stringContent1, $stringContent2, false));
        $this->assertTrue(StringUtils::greaterThan($stringContent1, $stringContent2, true));
    }

    /**
     * Test the StringUtils::greaterThanEqualTo() method.
     */
    public function testGreaterThanEqualTo(): void
    {
        $stringContent1 = "This is a test";
        $stringContent2 = "This is a TEST";
        $numericContent1 = "1.01";
        $numericContent2 = "1.02";

        $this->assertTrue(StringUtils::greaterThanEqualTo($numericContent2, $numericContent1, false));
        $this->assertTrue(StringUtils::greaterThanEqualTo($numericContent1, $numericContent1, false));
        $this->assertTrue(StringUtils::greaterThanEqualTo($numericContent2, $numericContent1, true));
        $this->assertTrue(StringUtils::greaterThanEqualTo($numericContent1, $numericContent1, true));
        $this->assertTrue(StringUtils::greaterThanEqualTo($stringContent1, $stringContent2, false));
        $this->assertTrue(StringUtils::greaterThanEqualTo($stringContent1, $stringContent2, true));
    }

    /**
     * Test the StringUtils::differentThan() method.
     */
    public function testDifferentThan(): void
    {
        $stringContent1 = "This is a test";
        $stringContent2 = "This is a TEST";
        $stringContent3 = "Different string";
        $numericContent1 = "1.01";
        $numericContent2 = "1.02";

        $this->assertTrue(StringUtils::differentThan($stringContent1, $stringContent3, true));
        $this->assertTrue(StringUtils::differentThan($stringContent1, $stringContent3, false));
        $this->assertTrue(StringUtils::differentThan($numericContent1, $numericContent2, true));
        $this->assertTrue(StringUtils::differentThan($numericContent1, $numericContent2, false));
        $this->assertTrue(StringUtils::differentThan($stringContent1, $stringContent2, true));
        $this->assertFalse(StringUtils::differentThan($stringContent1, $stringContent2, false));
    }

    /**
     * Test the StringUtils::contains() method.
     */
    public function testContains(): void
    {
        $stringContent1 = "This is a test";
        $stringContent2 = "Different string";

        $this->assertTrue(StringUtils::contains($stringContent1, "is a", true));
        $this->assertTrue(StringUtils::contains($stringContent1, "is a", false));
        $this->assertTrue(StringUtils::contains($stringContent1, "iS A", false));
        $this->assertTrue(StringUtils::contains($stringContent2, "strin", true));
        $this->assertTrue(StringUtils::contains($stringContent2, "strin", false));
        $this->assertTrue(StringUtils::contains($stringContent2, "STRIN", false));
        $this->assertFalse(StringUtils::contains($stringContent1, "iS A", true));
        $this->assertFalse(StringUtils::contains($stringContent1, "string", true));
        $this->assertFalse(StringUtils::contains($stringContent2, "is a", true));
        $this->assertFalse(StringUtils::contains($stringContent2, "STRIN", true));
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
     * Test the StringUtils::startsWith() method with additional check on upper or lower cases.
     */
    public function testStartsWithCaseCheck(): void
    {
        $this->assertTrue(StringUtils::startsWith("This is a test", "This is", false));
        $this->assertTrue(StringUtils::startsWith("This is a test", "this is", false));
        $this->assertTrue(StringUtils::startsWith("This is a test", "ThiS Is", false));
        $this->assertTrue(StringUtils::startsWith("This is a test", "This is", true));
        $this->assertFalse(StringUtils::startsWith("This is a test", "this is", true));
        $this->assertFalse(StringUtils::startsWith("This is a test", "ThiS Is", true));
        $this->assertFalse(StringUtils::startsWith("This is a test", "Another string", true));
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
     * Test the StringUtils::endsWith() method with additional check on upper or lower cases.
     */
    public function testEndsWithCaseCheck(): void
    {
        $this->assertTrue(StringUtils::endsWith("This is a test", "", false));
        $this->assertTrue(StringUtils::endsWith("This is a test", "", true));
        $this->assertTrue(StringUtils::endsWith("This is a test", "test", false));
        $this->assertTrue(StringUtils::endsWith("This is a test", "TeSt", false));
        $this->assertFalse(StringUtils::endsWith("This is a test", "TeSt", true));
        $this->assertFalse(StringUtils::endsWith("This is a test", "Another string", false));
        $this->assertFalse(StringUtils::endsWith("This is a test", "Another string", true));
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