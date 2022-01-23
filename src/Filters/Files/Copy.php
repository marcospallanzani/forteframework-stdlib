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

namespace Forte\Stdlib\Filters\Files;

use Forte\Stdlib\Exceptions\GeneralException;
use Laminas\Filter\File\Rename;

/**
 * This class copies a source file to a given destination.
 *
 * @package Forte\Stdlib\Filters\Files
 * @author  Marco Spallanzani <forteframework@gmail.com>
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
        if (true === is_string($file)) {
            return $file;
        }

        if (true !== $this->copyFileToDestination($file['source'], $file['target'])) {
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
