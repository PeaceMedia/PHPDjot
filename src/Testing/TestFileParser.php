<?php

/**
 * Parse a Djot test file into a series of test cases.
 */

declare(strict_types=1);

namespace PeaceMedia\PHPDjot\Testing;

use PeaceMedia\PHPDjot\Testing\TestCase;
use PeaceMedia\PHPDjot\Testing\TestFileUnreadableException;

final class TestFileParser
{
    /**
     * Parse the file at the path and return an array of test cases.
     */
    public function parse(string $filePath): array
    {
        $fp = fopen($filePath, 'r');
        if (!$fp) {
            throw new TestFileUnreadableException("Test file {$filePath} could not be read.");
        }

        $testCases = [];

        // For simplicity's sake this is currently written much the same style
        // that the Lua code is written. I probably wouldn't have written it
        // quite this way, but…

        $lineNum = 0;

        while (true) {
            $input = '';
            $output = '';
            $line = fgets($fp);
            $preTextLines = [];
            $lineNum++;
            while ($line && !preg_match('/^```/', $line)) {
                $preTextLines[] = $line;
                $line = fgets($fp);
                $lineNum++;
            }

            $testLineNum = $lineNum;
            if (!$line) {
                break;
            }
            preg_match('/^(`+)\s*(.*)$/', $line, $matches);
            $ticks = $matches[1];
            $optionsStr = $matches[2] ?? '';
            $options = [];
            foreach (
                [
                    'p' => TestOptions::SOURCE_POS,
                    'm' => TestOptions::EVENTS,
                    'a' => TestOptions::AST,
                ] as $char => $option
            ) {
                if (str_contains($optionsStr, $char)) {
                    $options[] = $option;
                }
            }

            // Read the input. It ends on a line with a dot or an exclamation
            // point, but the latter case is for filters which will themselves
            // be written in Lua, so we don't really support those. Test files
            // containing filters shouldn't be run through this code.
            $line = fgets($fp);
            $lineNum++;
            while (!preg_match('/^[\.\!]$/', $line)) {
                $input .= $line . "\n";
                $line = fgets($fp);
                $lineNum++;
            }

            if ($line === '!') {
                throw new \Exception("Test in {$filePath} contains a filter.");
            }

            // Read the output.
            $line = fgets($fp);
            $lineNum++;
            while (!preg_match('/^' . $ticks . '/', $line)) {
                $output .= $line . "\n";
                $line = fgets($fp);
                $lineNum++;
            }

            $testCases[] = new TestCase(
                $testLineNum,
                implode("\n", $preTextLines),
                $options, // Need to calculate this
                $input,
                $output
            );
        }

        return $testCases;
    }
}
