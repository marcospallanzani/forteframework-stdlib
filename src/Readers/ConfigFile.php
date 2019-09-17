<?php

namespace Forte\Stdlib\Readers;

use Forte\Stdlib\ArrayableInterface;
use Forte\Stdlib\ArrayUtils;
use Forte\Stdlib\Exceptions\GeneralException;
use Forte\Stdlib\Exceptions\MissingKeyException;
use Forte\Stdlib\FileUtils;

/**
 * Class ConfigFile.
 *
 * @package Forte\Stdlib\Readers
 */
class ConfigFile implements ArrayableInterface
{
    /**
     * @var array
     */
    protected $configEntries = [];

    /**
     * @var string
     */
    protected $configFilePath;

    /**
     * ConfigFile constructor.
     *
     * @param string $configFilePath The config file to load.
     * @param string $contentType The config file type (accepted values
     * are the FileParser constants starting with "CONTENT_TYPE").
     *
     * @throws GeneralException
     */
    public function __construct(string $configFilePath, string $contentType)
    {
        $this->configFilePath = $configFilePath;
        $this->configEntries  = FileUtils::parseFile($configFilePath, $contentType);
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
     * @return array An array representation of this AbstractAction subclass instance.
     */
    public function toArray(): array
    {
        return ArrayUtils::variablesToArray(get_object_vars($this));
    }

    /**
     * Return the original config file path.
     *
     * @return string
     */
    public function getConfigFilePath(): string
    {
        return $this->configFilePath;
    }
}
