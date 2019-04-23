<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\ReverseArrayIteration
 */
class ReverseArrayIterationTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_shortCircuitWhenEmpty(): void
    {
        $iteration = new ReverseArrayIteration(Types::nothing(), []);
        self::assertFalse($iteration->hasNext());
    }
}
