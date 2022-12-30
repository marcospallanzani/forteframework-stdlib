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
 */
class Copy extends Rename
{
    /**
     * Copy the file $value to the new name previously set. Return the file $value,
     * removing all but digit characters.
     *
     * @param mixed $value Full path of file to change or $_FILES data array
     *
     * @return string The new filename which has been set.
     *
     * @throws GeneralException
     */
    public function filter(mixed $value): mixed
    {
        if (true === \is_string($value)) {
            /** @var mixed $file */
            $file = $this->getNewName($value, true);

            if (true === \is_array($file)
                && true === \array_key_exists('source', $file)
                && true === \array_key_exists('target', $file)
            ) {
                if (true !== $this->copyFileToDestination($file['source'], $file['target'])) {
                    throw new GeneralException("An error occurred while copying file {$file['source']}.");
                }

                return $file['target'];
            }
        }

        return $value;
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
