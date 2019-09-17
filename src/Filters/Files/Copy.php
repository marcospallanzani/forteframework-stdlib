<?php

namespace Forte\Stdlib\Filters\Files;

use Forte\Stdlib\Exceptions\GeneralException;
use Zend\Filter\File\Rename;

/**
 * Class Copy. This class copies a source file to a given destination.
 *
 * @package Forte\Stdlib\Filters\Files
 */
class Copy extends Rename
{
    /**
     * Copy the file $value to the new name set before. Return the file $value,
     * removing all but digit characters
     *
     * @param  string|array $value Full path of file to change or $_FILES data array
     *
     * @return string|array The new filename which has been set.
     *
     * @throws GeneralException
     */
    public function filter($value)
    {
        if (! is_scalar($value) && ! is_array($value)) {
            return $value;
        }

        $file = $this->getNewName($value, true);
        if (is_string($file)) {
            return $file;
        }

        $result = $this->copyFileToDestination($file['source'], $file['target']);
        if ($result !== true) {
            throw new GeneralException(sprintf(
                "File '%s' could not be copied. An error occurred while processing the file.",
                $value
            ));
        }

        return $file['target'];
    }

    /**
     * Copy the given source file to the give target.
     *
     * @param string $fileSource The file to copy.
     * @param string $fileTarget The target file.
     *
     * @return bool True if the file was copied; false otherwise.
     */
    protected function copyFileToDestination(string $fileSource, string $fileTarget): bool
    {
        return copy($fileSource, $fileTarget);
    }
}
