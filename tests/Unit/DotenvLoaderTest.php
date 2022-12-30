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

use Forte\Stdlib\DotenvLoader;
use Forte\Stdlib\Exceptions\GeneralException;

/**
 * @package Forte\Stdlib\Tests\Unit
 */
class DotenvLoaderTest extends BaseTest
{
    /**
     * Test function DotenvLoader::loadIntoArray().
     *
     * @throws GeneralException
     */
    public function testLoadIntoArray(): void
    {
        $this->assertEquals(
            $this->configEnvArray,
            DotenvLoader::loadIntoArray(\implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', '.env.parsetest']))
        );
    }

    /**
     * Test function DotenvLoader::loadIntoArray() with an empty file path: error expected.
     *
     * @throws GeneralException
     */
    public function testLoadIntoArrayEmptyFilePath(): void
    {
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage('The file path must be provided.');
        DotenvLoader::loadIntoArray('');
    }

    /**
     * Test function DotenvLoader::loadIntoArray() with a non-existent file: error expected.
     *
     * @throws GeneralException
     */
    public function testLoadIntoArrayNonExistentFile(): void
    {
        $filePath = \implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', 'xxx']);
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage("Unable to read the environment file [$filePath].");
        DotenvLoader::loadIntoArray($filePath);
    }

    /**
     * Test function DotenvLoader::loadIntoArray() with a badly-formed file: error expected.
     *
     * @throws GeneralException
     */
    public function testLoadIntoArrayBadlyFormedFile(): void
    {

        $filePath = \implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config', '.env.wrong']);
        $this->expectException(GeneralException::class);
        $this->expectExceptionMessage(
            "Error occurred while reading the file '$filePath'. Error message is: Failed " .
            'to parse dotenv file. Encountered an unexpected equals at [=value3].'
        );
        DotenvLoader::loadIntoArray($filePath);
    }

    /**
     * Test function DotenvLoader::getLineFromVariables().
     */
    public function testGetLineFromVariables(): void
    {
        $this->assertEquals('key=value', DotenvLoader::getLineFromVariables('key', 'value'));
        $this->assertEquals('key="value with spaces"', DotenvLoader::getLineFromVariables('key', 'value with spaces'));
    }
}
