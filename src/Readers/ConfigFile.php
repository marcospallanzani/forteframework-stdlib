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

namespace Forte\Stdlib\Readers;

use Forte\Stdlib\ArrayableInterface;
use Forte\Stdlib\ArrayUtils;
use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\MissingKeyException;
use Forte\Stdlib\FileUtils;

/**
 * Reads a configuration file. Different file types are supported. Please refer to FileUtils
 * content type constants for a list of supported file types.
 *4
 * @package Forte\Stdlib\Readers
 */
class ConfigFile implements ArrayableInterface
{
    /** @var array<mixed, mixed> */
    protected array $configEntries = [];

    /** @var string */
    protected string $configFilePath;

    /**
     * @param string $configFilePath The config file to load.
     * @param string $contentType The config file type (accepted values
     * are the FileUtils constants starting with "CONTENT_TYPE").
     *
     * @throws GeneralException
     */
    public function __construct(string $configFilePath, string $contentType)
    {
        $this->configFilePath = $configFilePath;
        $this->configEntries = FileUtils::parseFile($configFilePath, $contentType);
    }

    /**
     * Return the API configuration value for the given key;
     * if not defined, an error will be thrown.
     *
     * @param string $key The configuration key.
     *
     * @return mixed The value found for the given key.
     *
     * @throws MissingKeyException The key was not found.
     */
    public function getValue(string $key)
    {
        return ArrayUtils::getValueFromArray($key, $this->configEntries);
    }

    /**
     * Return an array representation of this AbstractAction subclass instance.
     *
     * @return array<mixed, mixed> An array representation of this AbstractAction subclass instance.
     */
    public function toArray(): array
    {
        return ArrayUtils::variablesToArray(\get_object_vars($this));
    }

    /**
     * Return the original config file path.
     *
     * @return string The original config file path.
     */
    public function getConfigFilePath(): string
    {
        return $this->configFilePath;
    }
}
