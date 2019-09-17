<?php

namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\ArrayableInterface;
use Forte\Stdlib\ClassAccessTrait;
use Forte\Stdlib\Exceptions\ThrowErrorsTrait;
use Forte\Stdlib\FileUtils;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseTest.
 *
 * @package Tests\Unit
 */
abstract class BaseTest extends TestCase
{
    /**
     * Tests constants
     */
    const BASE_TEST_MESSAGE = "error message %s.";

    /**
     * @var array
     */
    protected $configFileArray = [
        "key1" => "value1",
        "key2" => [
            "key3" => "value3",
            "key4" => [
                "key5" => "value5"
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $configEnvArray = [
        "key1" => "value1",
        "key3" => "value3",
        "key5" => "value5",
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
            [__DIR__ . "/../data/configfiles/parsetest.ini", FileUtils::CONTENT_TYPE_INI, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [__DIR__ . "/../data/configfiles/parsetest.json", FileUtils::CONTENT_TYPE_JSON, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [__DIR__ . "/../data/configfiles/parsetest.php", FileUtils::CONTENT_TYPE_ARRAY, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [__DIR__ . "/../data/configfiles/parsetest.xml", FileUtils::CONTENT_TYPE_XML, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [__DIR__ . "/../data/configfiles/parsetest.yml", FileUtils::CONTENT_TYPE_YAML, $this->configFileArray, $existentKeys, $nonExistentKeys],
            [__DIR__ . "/../data/configfiles/.env.parsetest", FileUtils::CONTENT_TYPE_ENV, $this->configEnvArray, ['key1' => 'value1', 'key3' => 'value3', 'key5' => 'value5'], $nonExistentKeys],
        ];
    }

    /**
     * Returns an anonymous class instance to test ThrowErrorsTrait.
     *
     * @return ArrayableInterface
     */
    protected function getAnonymousClass(): ArrayableInterface
    {
        return new class() implements ArrayableInterface {
            use ThrowErrorsTrait, ClassAccessTrait;

            protected $objects = [];

            public function addArrayableObject(ArrayableInterface $object): void
            {
                $this->objects[] = $object;
            }

            public function toArray(): array
            {
                $toArray['objects'] = [];
                foreach ($this->objects as $object) {
                    $toArray['objects'][] = $object->toArray();
                }
                return $toArray;
            }
        };
    }
}
