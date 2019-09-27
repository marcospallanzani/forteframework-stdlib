<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\Exceptions\WrongParameterException;

/**
 * Class ValidationTraitTest.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class ValidationTraitTest extends BaseTest
{
    /**
     * @return array
     */
    public function parametersListProvider(): array
    {
        // parameter value |  accepted parameters | parameter message | expected result | expected exception
        return [
            ['a real comment', ['value1', 'value', 'a real comment'], 'comment', true, false],
            [10, [10, 'value', 'a real comment'], 'comment', true, false],
            ['xxx', ['value1', 'value', 'a real comment'], 'comment', false, true, "Unsupported comment with value xxx. Supported visibilities [value1, value, a real comment]."],
            [10, ['value1', 'value', 'a real comment'], 'comment', false, true, "Unsupported comment with value 10. Supported visibilities [value1, value, a real comment]."],
        ];
    }

    /**
     * Tests the ValidationTrait::validateParameterInAcceptedList() method.
     *
     * @dataProvider parametersListProvider
     *
     * @param mixed $parameter
     * @param array $acceptedParameters
     * @param string $parameterMessage
     * @param bool $expectedResult
     * @param bool $expectedException
     * @param string $errorMessage
     */
    public function testParametersList(
        $parameter,
        array $acceptedParameters,
        string $parameterMessage,
        bool $expectedResult,
        bool $expectedException,
        string $errorMessage = ""
    ): void
    {
        if ($expectedException) {
            $this->expectException(WrongParameterException::class);
            $this->expectExceptionMessage($errorMessage);
        }
        $class = $this->getAnonymousClass();
        $this->assertEquals($expectedResult, $class->validateParameterInAcceptedList(
            $parameter,
            $acceptedParameters,
            $parameterMessage
        ));
    }
}
