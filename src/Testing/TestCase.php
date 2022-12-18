<?php

/**
 * Store data about a test case and evaluate it.
 */

declare(strict_types=1);

namespace PeaceMedia\PHPDjot\Testing;

use PeaceMedia\PHPDjot\Testing\TestOptions;

final class TestCase
{
    /**
     * Whether the test passed. Null before the test runs.
     */
    private ?bool $passed = null;

    /**
     * What the output of the parsing process was. Null before test runs.
     */
    private ?string $output = null;

    public function __construct(
        /**
         * The line number in the test file that the "input" block of the test
         * begins on. Note that the "pre-text" if any will be above this line.
         */
        private int $lineNumber,
        /**
         * Text which appears before the "input" block on this test case. Used on
         * some tests for documentation, but blank on most tests.
         */
        private string $preText,
        /**
         * Some options dictating what the output of the test should look like.
         * Should contain TestOptions enum values.
         */
        private array $testOptions,
        /**
         * The input to the test.
         */
        private string $input,
        /**
         * The expected output of the test. What we compare our output to.
         */
        private string $expectedOutput
    ) {
        // Do nothing but stop the PSR-12 code evaluator from complaining
    }

    /**
     * Run the test by parsing and translating the output and comparing it to
     * the expected output.
     */
    public function test(): bool
    {
        // There shouldn't be a case where someone tries to run a test twice,
        // but just in case, just return the previous answer.
        if ($this->passed !== null) {
            return $this->passed;
        }

        assert($input);
        assert($expectedOutput);
        assert($lineNumber);

        // Parsing the input and comparing to output would happen here.
        // Just set the output as random nonsense for now
        $this->output = base64_encode(random_bytes(random_int(4, 12)));

        $this->passed = $this->output === $this->expectedOutput;
        return $this->testPassed();
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function getExpectedOutput(): string
    {
        return $this->expectedOutput;
    }

    public function getOutput(): string
    {
        assert($this->output !== null);
        return $this->output;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function getPreText(): string
    {
        return $this->preText;
    }

    public function getTestOptions(): array
    {
        return $this->testOptions;
    }

    public function testPassed(): bool
    {
        assert($this->passed !== null);
        return $this->passed;
    }
}
