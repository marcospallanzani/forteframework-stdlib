<?php

namespace Forte\Stdlib;

/**
 * Class DirectoryUtils.
 *
 * @package Forte\Stdlib
 */
class DirectoryUtils
{
    /**
     * Return an Iterator instance to iterate through the files in the given directory path
     * and in its sub-folders. It is possible to filter this list by file patterns and by
     * excluded sub-directories.
     *
     * @param string $directoryPath The directory path to explore.
     * @param array $filePatterns The file patterns to filter the file list.
     * @param array $excludedDirectories The list of directories to exclude from the final list.
     *
     * @return \Iterator
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
                    if (in_array($iterator->getSubPathName(), $excludedDirectories)) {
                        return false;
                    } else {
                        return true;
                    }
                }

                // Check the file pattern
                foreach ($filePatterns as $filePattern) {
                    if (fnmatch($filePattern, $iterator->getSubPathName())) {
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
