<?php

/**
 * Parses reference implementation's test files and compares our output.
 */

declare(strict_types=1);

namespace PeaceMedia\PHPDjot\Testing;

use PeaceMedia\PHPDjot\Testing\TestFileParser;
use PeaceMedia\PHPDjot\Testing\TestFileUnreadableException;

final class TestRunner
{
    private string $dirPath;
    private array $testFiles;

    public function __construct(string $dirPath)
    {
        $dirPath = realpath($dirPath);

        if (!is_dir($dirPath)) {
            // Should eventually throw exceptions instead that the test script
            // turns into error messages.
            throw new TestFileUnreadableException('Test directory does not exist.');
        }

        if (!is_readable($dirPath)) {
            throw new TestFileUnreadableException('Test directory is not readable.');
        }

        $fi = new \FilesystemIterator($dirPath);
        foreach ($fi as $item) {
            if ($item->isFile() && $item->getExtension() === 'test') {
                if ($item->getFilename() === 'filters.test') {
                    // The filters are written in Lua themselves and we can't
                    // execute them. At any rate these tests are more for the
                    // specific Lua implementation rather than Djot in general.
                    continue;
                }
                $fullPath = $item->getRealPath();
                if (!$item->isReadable()) {
                    throw new TestFileUnreadableException("Test file at {$fullPath} is not readable.");
                }

                $this->testFiles[] = $item;
            }
        }
    }

    public function runTests(): array
    {
        $parser = new TestFileParser();
        $testsByFile = [];
        foreach ($this->testFiles as $testFile) {
            $tests = $parser->parse($testFile->getRealPath());
            foreach ($tests as $test) {
                $test->test();
            }
            $testsByFile[$testFile->getFilename()] = $tests;
        }
        return $testsByFile;
    }

    /**
     * Helper function to print a message at standard console width.
     */
    private function wecho(string $message)
    {
        echo wordwrap($message, 40, PHP_EOL) . PHP_EOL;
    }
}
