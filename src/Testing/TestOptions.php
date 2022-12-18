<?php

/**
 * Enum for options specifying how a test case should be evaluated.
 */

declare(strict_types=1);

namespace PeaceMedia\PHPDjot\Testing;

enum TestOptions
{
    // p
    case SOURCE_POS;
    // m
    case EVENTS;
    // a
    case AST;
}
