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

use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\WrongParameterException;

/**
 * @package Forte\Stdlib\Tests\Unit
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class ValidationTraitTest extends BaseTest
{
    public function parametersListProvider(): array
    {
        // parameter value |  accepted parameters | parameter name | expected result | expected exception | error message
        return [
            ['a real comment', ['value1', 'value', 'a real comment'], 'comment', true, false],
            [10, [10, 'value', 'a real comment'], 'comment', true, false],
            [
                'xxx',
                ['value1', 'value', 'a real comment'],
                'comment',
                false,
                true,
                'Unsupported comment with value [xxx]. Supported parameters [value1, value, a real comment].',
            ],
            [
                10,
                ['value1', 'value', 'a real comment'],
                'comment',
                false,
                true,
                'Unsupported comment with value [10]. Supported parameters [value1, value, a real comment].',
            ],
            [
                '',
                ['value1', 'value', 'a real comment'],
                'comment',
                false,
                true,
                'Unsupported comment with value []. Supported parameters [value1, value, a real comment].',
            ],
        ];
    }

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

    public function stringListProvider(): array
    {
        // list |  list name | expected result | expected exception | error message
        return [
            [['test', 'test2', 'test3'], 'tests', true, false],
            [['test', 0, 'test3'], 'tests', false, true, 'tests list should contain only string values.'],
            [['test', false, 'test3'], 'tests', false, true, 'tests list should contain only string values.'],
        ];
    }

    public function objectListProvider(): array
    {
        // list |  list name | expected result | expected exception | error message
        return [
            [
                [new GeneralException(), new GeneralException(), new GeneralException()],
                GeneralException::class,
                'exceptions',
                true,
                false,
            ],
            [
                [new WrongParameterException(), new WrongParameterException(), new WrongParameterException()],
                WrongParameterException::class,
                'exceptions',
                true,
                false,
            ],
            [
                [new WrongParameterException(), new WrongParameterException(), new GeneralException()],
                WrongParameterException::class,
                'exceptions',
                false,
                true,
                'exceptions list should contain only ' . WrongParameterException::class . ' instances.',
            ],
            [
                [new \stdClass(), new WrongParameterException(), new GeneralException()],
                WrongParameterException::class,
                'exceptions',
                false,
                true,
                'exceptions list should contain only ' . WrongParameterException::class . ' instances.',
            ],
            [
                [1, new WrongParameterException(), new WrongParameterException()],
                WrongParameterException::class,
                'exceptions',
                false,
                true,
                'exceptions list should contain only ' . WrongParameterException::class . ' instances.',
            ],
        ];
    }

    /**
     * Tests the ValidationTrait::validateParameterInAcceptedList() method.
     *
     * @dataProvider parametersListProvider
     *
     * @param mixed $parameter The parameter to be checked.
     * @param array $acceptedParameters The accepted parameters.
     * @param string $parameterName The parameter name (for log purpose only).
     * @param bool $expectedResult Whether the given parameter is contained in the given list of accepted parameters.
     * @param bool $expectedException Whether an exception is expected.
     * @param string $errorMessage The expected exception message.
     */
    public function testParametersList(
        $parameter,
        array $acceptedParameters,
        string $parameterName,
        bool $expectedResult,
        bool $expectedException,
        string $errorMessage = ''
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
     * @param mixed $list A list of objects/variables to be checked.
     * @param string $listName The list name (for log purpose only).
     * @param bool $expectedResult Whether the given list contains only strings.
     * @param bool $expectedException Whether an exception is expected.
     * @param string $errorMessage The expected exception message.
     */
    public function testStringList(
        $list,
        string $listName,
        bool $expectedResult,
        bool $expectedException,
        string $errorMessage = ''
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
     * @param mixed $parameter The parameter to be checked.
     * @param string $parameterName The parameter name (for log purpose only).
     * @param bool $expectedResult Whether the given parameter is not empty.
     * @param bool $expectedException Whether an exception is expected.
     * @param string $errorMessage The expected exception message.
     */
    public function testNonEmptyParameter(
        $parameter,
        string $parameterName,
        bool $expectedResult,
        bool $expectedException,
        string $errorMessage = ''
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
     * @param array $list A list of objects to be checked.
     * @param string $expectedClass The expected class of the given objects.
     * @param string $parameterName An identifier of the given object list (for log purpose only).
     * @param bool $expectedResult Whether the given objects are all of the given type.
     * @param bool $expectedException Whether an exception is expected.
     * @param string $errorMessage The expected exception message.
     */
    public function testObjectList(
        array $list,
        string $expectedClass,
        string $parameterName,
        bool $expectedResult,
        bool $expectedException,
        string $errorMessage = ''
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
