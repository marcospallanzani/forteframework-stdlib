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

/**
 * Set of methods to handle a directory and its content.
 *
 * @package Forte\Stdlib
 * @author  Marco Spallanzani <forteframework@gmail.com>
 */
class DirectoryUtils
{
    /**
     * Return an Iterator instance to iterate through the files in the given directory path
     * and in its sub-folders. It is possible to filter this list by file patterns and by
     * excluded sub-directories.
     *
     * @param string $directoryPath The directory path to explore.
     * @param array<string> $filePatterns The file patterns to filter the file list.
     * @param array<string> $excludedDirectories The list of directories to exclude from the final list.
     *
     * @return \Iterator An iterator instance on all the files in the given directory path.
     */
    public static function getFilesList(
        string $directoryPath,
        array $filePatterns = [],
        array $excludedDirectories = []
    ): \Iterator
    {
        $dir = new \RecursiveDirectoryIterator($directoryPath, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveCallbackFilterIterator(
            $dir,
            function ($current, $key, $iterator) use ($filePatterns, $excludedDirectories) {
                // Allow recursion only on those directories that are not in the exclude list
                if ($iterator->hasChildren()) {
                    if (\in_array($iterator->getSubPathName(), $excludedDirectories, true)) {
                        return false;
                    }
                    return true;
                }

                // Check the file pattern
                foreach ($filePatterns as $filePattern) {
                    if (\fnmatch($filePattern, $iterator->getSubPathName())) {
                        return true;
                    }
                }

                /**
                 * If we get here, it means that EITHER no file patterns are specified OR no file
                 * patterns were matched. In the former case, it means that the current file is
                 * valid (it is not in an excluded directory); in the latter case, it means that
                 * the current file hasn't matched any of the specified file patterns; thus, it is
                 * excluded from the final result list.
                 */
                if ($filePatterns) {
                    return false;
                }
                return true;
            }
        );

        return new \RecursiveIteratorIterator($files);
    }
}
