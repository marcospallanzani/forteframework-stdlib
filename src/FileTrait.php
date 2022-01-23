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

namespace Forte\Stdlib;

use Forte\Stdlib\Exceptions\GeneralException;
use Laminas\Validator\File\NotExists;

/**
 * Trait FileTrait.
 *
 * Trait with helper methods for all file access actions.
 *
 * @package Forte\Stdlib
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
trait FileTrait
{
    /**
     * Checks if the given file path points to an existing file.
     *
     * @param string $filePath The file path to be checked
     * @param bool $raiseError Whether an exception should be thrown if the file does not exist.
     *
     * @return bool Returns true if the given file path points to an existing file; false otherwise.
     *
     * @throws GeneralException
     */
    public function fileExists(string $filePath, bool $raiseError = true): bool
    {
        // We check if the given file exists
        $notExists = new NotExists();
        if (true === $notExists->isValid($filePath)) {
            if ($raiseError) {
                throw new GeneralException(sprintf(
                    "The file '%s' does not exist.",
                    $filePath
                ));
            }
            return false;
        }

        return true;
    }
}
