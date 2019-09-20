<?php


namespace Forte\Stdlib\Tests\Unit;

use Forte\Stdlib\DirectoryUtils;

/**
 * Class DirectoryUtilsTest.
 *
 * @package Forte\Stdlib\Tests\Unit
 */
class DirectoryUtilsTest extends BaseTest
{
    /**
     * Test constants.
     */
    const TEST_BASE_DIR_TMP    = __DIR__;
    const TEST_TEST_DIR_TMP    = self::TEST_BASE_DIR_TMP . '/level1';
    const TEST_NESTED_DIR_TMP  = self::TEST_TEST_DIR_TMP . '/level2';
    const TEST_FILE_LEVEL1_PHP = self::TEST_TEST_DIR_TMP . '/test1.php';
    const TEST_FILE_LEVEL1_INI = self::TEST_TEST_DIR_TMP . '/test1.ini';
    const TEST_FILE_LEVEL1_XML = self::TEST_TEST_DIR_TMP . '/test1.xml';
    const TEST_FILE_LEVEL2_PHP = self::TEST_NESTED_DIR_TMP . '/test2.php';
    const TEST_FILE_LEVEL2_INI = self::TEST_NESTED_DIR_TMP . '/test2.ini';

    /**
     * @var array
     */
    protected $files = [
        self::TEST_FILE_LEVEL1_PHP,
        self::TEST_FILE_LEVEL1_INI,
        self::TEST_FILE_LEVEL1_XML,
        self::TEST_FILE_LEVEL2_PHP,
        self::TEST_FILE_LEVEL2_INI,
    ];

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        // We have to copy the template file, which will be deleted by this test
        if (is_dir(self::TEST_TEST_DIR_TMP)) {
            @rmdir(self::TEST_TEST_DIR_TMP);
        }
        @mkdir(self::TEST_TEST_DIR_TMP);

        if (is_dir(self::TEST_NESTED_DIR_TMP)) {
            @rmdir(self::TEST_NESTED_DIR_TMP);
        }
        @mkdir(self::TEST_NESTED_DIR_TMP);

        foreach ($this->files as $file) {
            @file_put_contents($file, "TEST");
        }
    }

    /**
     * This method is called after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($this->files as $file) {
            @unlink($file);
        }
        @rmdir(self::TEST_NESTED_DIR_TMP);
        @rmdir(self::TEST_TEST_DIR_TMP);
    }

    /**
     * Data provider for files tests.
     *
     * @return array
     */
    public function filesProvider(): array
    {
        // Patterns | Excluded Directories | Expected files
        return [
            [[], [], $this->files],
            [[], ['level2'], [self::TEST_FILE_LEVEL1_PHP, self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL1_XML]],
            [['*test2.php'], [], [self::TEST_FILE_LEVEL2_PHP]],
            [['*test2.php'], ['level2'], []],
            [['*.php'], ['level2'], [self::TEST_FILE_LEVEL1_PHP]],
            [['*.php', '*.ini'], ['level2'], [self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL1_PHP]],
            [['*.php', '*.ini', '*.json'], ['level2'], [self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL1_PHP]],
            [['*.php', '*.ini', '*.xml'], ['level2'], [self::TEST_FILE_LEVEL1_XML, self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL1_PHP]],
            [['*.php'], [], [self::TEST_FILE_LEVEL1_PHP, self::TEST_FILE_LEVEL2_PHP]],
            [['*.php', '*.ini'], [], [self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL2_INI, self::TEST_FILE_LEVEL1_PHP, self::TEST_FILE_LEVEL2_PHP]],
            [['*.php', '*.ini', '*.json'], [], [self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL2_INI, self::TEST_FILE_LEVEL1_PHP, self::TEST_FILE_LEVEL2_PHP]],
            [['*.php', '*.ini', '*.xml'], [], $this->files],
            [['*.ini'], [], [self::TEST_FILE_LEVEL1_INI, self::TEST_FILE_LEVEL2_INI]],
            [['*.ini'], ['level2'], [self::TEST_FILE_LEVEL1_INI]],
            [['*.xml'], [], [self::TEST_FILE_LEVEL1_XML]],
            [['*.xml'], ['level2'], [self::TEST_FILE_LEVEL1_XML]],
        ];
    }

    /**
     * Test function DirectoryUtils::getFilesList().
     *
     * @dataProvider filesProvider
     *
     * @param array $filePatterns
     * @param array $excludedDirectories
     * @param array $expectedFiles
     */
    public function testGetFilesListAllFiles(
        array $filePatterns,
        array $excludedDirectories,
        array $expectedFiles
    ): void
    {
        $files = DirectoryUtils::getFilesList(self::TEST_TEST_DIR_TMP, $filePatterns, $excludedDirectories);
        $this->assertCount(count($expectedFiles), $files);
        foreach ($files as $file) {
            $this->assertTrue(in_array($file->getPathName(), $expectedFiles));
        }
    }
}
