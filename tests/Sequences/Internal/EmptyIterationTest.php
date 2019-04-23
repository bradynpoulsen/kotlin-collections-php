<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\EmptyIteration
 */
class EmptyIterationTest extends TestCase
{
    /**
     * @test
     */
    public function getType_notAvailable(): void
    {
        $this->expectException(InvalidStateException::class);
        (new EmptyIteration())->getType();
    }

    /**
     * @test
     */
    public function next_notAvailable(): void
    {
        $this->expectException(NoSuchElementException::class);
        (new EmptyIteration())->next();
    }
}
