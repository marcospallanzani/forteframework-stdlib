<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\Exceptions\GeneralException;
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
        // parameter value |  accepted parameters | parameter name | expected result | expected exception | error message
        return [
            ['a real comment', ['value1', 'value', 'a real comment'], 'comment', true, false],
            [10, [10, 'value', 'a real comment'], 'comment', true, false],
            ['xxx', ['value1', 'value', 'a real comment'], 'comment', false, true, "Unsupported comment with value [xxx]. Supported visibilities [value1, value, a real comment]."],
            [10, ['value1', 'value', 'a real comment'], 'comment', false, true, "Unsupported comment with value [10]. Supported visibilities [value1, value, a real comment]."],
            ['', ['value1', 'value', 'a real comment'], 'comment', false, true, "Unsupported comment with value []. Supported visibilities [value1, value, a real comment]."],
        ];
    }

    /**
     * @return array
     */
    public function nonEmptyProvider(): array
    {
        // parameter value |  parameter name | expected result | expected exception | error message
        return [
            [10, 'comment', true, false],
            [0, 'comment', true, false],
            [1, 'comment', true, false],
            [false, 'comment', true, false],
            [true, 'comment', true, false],
            ['test', 'comment', true, false],
            ['', 'comment', false, true, 'Parameter comment cannot be empty'],
        ];
    }

    /**
     * @return array
     */
    public function stringListProvider(): array
    {
        // list |  list name | expected result | expected exception | error message
        return [
            [['test', 'test2', 'test3'], 'tests', true, false],
            [['test', 0, 'test3'], 'tests', false, true, 'tests list should contain only string values.'],
            [['test', false, 'test3'], 'tests', false, true, 'tests list should contain only string values.'],
        ];
    }

    /**
     * @return array
     */
    public function objectListProvider(): array
    {
        // list |  list name | expected result | expected exception | error message
        return [
            [[new GeneralException(), new GeneralException(), new GeneralException()], GeneralException::class, 'exceptions', true, false],
            [[new WrongParameterException(), new WrongParameterException(), new WrongParameterException()], WrongParameterException::class, 'exceptions', true, false],
            [[new WrongParameterException(), new WrongParameterException(), new GeneralException()], WrongParameterException::class, 'exceptions', false, true, "exceptions list should contain only ".WrongParameterException::class." instances."],
            [[new \stdClass(), new WrongParameterException(), new GeneralException()], WrongParameterException::class, 'exceptions', false, true, "exceptions list should contain only ".WrongParameterException::class." instances."],
            [[1, new WrongParameterException(), new WrongParameterException()], WrongParameterException::class, 'exceptions', false, true, "exceptions list should contain only ".WrongParameterException::class." instances."],
        ];
    }

    /**
     * Tests the ValidationTrait::validateParameterInAcceptedList() method.
     *
     * @dataProvider parametersListProvider
     *
     * @param mixed $parameter
     * @param array $acceptedParameters
     * @param string $parameterName
     * @param bool $expectedResult
     * @param bool $expectedException
     * @param string $errorMessage
     */
    public function testParametersList(
        $parameter,
        array $acceptedParameters,
        string $parameterName,
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
            $parameterName
        ));
    }

    /**
     * Tests the ValidationTrait::validateStringList() method.
     *
     * @dataProvider stringListProvider
     *
     * @param mixed $list
     * @param string $listName
     * @param bool $expectedResult
     * @param bool $expectedException
     * @param string $errorMessage
     */
    public function testStringList(
        $list,
        string $listName,
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
        $this->assertEquals($expectedResult, $class->validateStringList(
            $list,
            $listName
        ));
    }

    /**
     * Tests the ValidationTrait::validateNonEmptyParameter() method.
     *
     * @dataProvider nonEmptyProvider
     *
     * @param $parameter
     * @param string $parameterName
     * @param bool $expectedResult
     * @param bool $expectedException
     * @param string $errorMessage
     */
    public function testNonEmptyParameter(
        $parameter,
        string $parameterName,
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
        $this->assertEquals($expectedResult, $class->validateNonEmptyParameter(
            $parameter,
            $parameterName
        ));
    }

    /**
     * Tests the ValidationTrait::validateObjectList() method.
     *
     * @dataProvider objectListProvider
     *
     * @param array $list
     * @param string $expectedClass
     * @param string $parameterName
     * @param bool $expectedResult
     * @param bool $expectedException
     * @param string $errorMessage
     */
    public function testObjectList(
        array $list,
        string $expectedClass,
        string $parameterName,
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
        $this->assertEquals($expectedResult, $class->validateObjectList(
            $list,
            $expectedClass,
            $parameterName
        ));
    }
}
