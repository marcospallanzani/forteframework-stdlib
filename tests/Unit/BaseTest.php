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

use Forte\Stdlib\ArrayableInterface;
use Forte\Stdlib\ClassAccessTrait;
use Forte\Stdlib\Exceptions\ThrowErrorsTrait;
use Forte\Stdlib\FileUtils;
use Forte\Stdlib\ValidationTrait;
use PHPUnit\Framework\TestCase;

/**
 * @package Tests\Unit
 */
abstract class BaseTest extends TestCase
{
    /**
     * Tests constants
     */
    public const BASE_TEST_MESSAGE = 'error message %s.';

    /** @var array */
    protected $configFileArray = [
        'key1' => 'value1',
        'key2' => [
            'key3' => 'value3',
            'key4' => [
                'key5' => 'value5',
            ],
        ],
    ];

    /** @var array */
    protected $configEnvArray = [
        'key1' => 'value1',
        'key3' => 'value3',
        'key5' => 'value5',
    ];

    /**
     * Data provider for all tests that require configuration files.
     *
     * @return array
     */
    public function configFilesProvider(): array
    {
        $existentKeys = ['key1' => 'value1', 'key2.key3' => 'value3', 'key2.key4.key5' => 'value5'];
        $nonExistentKeys = ['key1.key99'];
        return [
            // File path | content type | expected content | check existent keys | check non-existent keys
            [\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', 'parsetest.ini']), FileUtils::CONTENT_TYPE_INI, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', 'parsetest.json']), FileUtils::CONTENT_TYPE_JSON, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', 'parsetest.php']), FileUtils::CONTENT_TYPE_ARRAY, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', 'parsetest.xml']), FileUtils::CONTENT_TYPE_XML, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', 'parsetest.yml']), FileUtils::CONTENT_TYPE_YAML, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', '.env.parsetest']), FileUtils::CONTENT_TYPE_ENV, $this->configEnvArray, ['key1' => 'value1', 'key3' => 'value3', 'key5' => 'value5'], $nonExistentKeys],
        ];
    }

    /**
     * Returns an anonymous class instance to test ThrowErrorsTrait.
     *
     * @return ArrayableInterface A test instance of ArrayableInterface
     */
    protected function getAnonymousClass(): ArrayableInterface
    {
        return new class() implements ArrayableInterface {
            use ThrowErrorsTrait;
            use ClassAccessTrait;
            use ValidationTrait;

            protected $objects = [];

            public function addArrayableObject(ArrayableInterface $object): void
            {
                $this->objects[] = $object;
            }

            public function toArray(): array
            {
                $toArray = [];
                $toArray['objects'] = [];
                foreach ($this->objects as $object) {
                    $toArray['objects'][] = $object->toArray();
                }
                return $toArray;
            }
        };
    }
}
