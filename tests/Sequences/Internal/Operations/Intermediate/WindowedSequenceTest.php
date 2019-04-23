<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\WindowedSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Types\Internal\StandardType
 * @covers \BradynPoulsen\Kotlin\Types\Types
 */
class WindowedSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function chunked_grabsBatchesOfItems(): void
    {
        self::assertEquals(
            [
                [1, 2, 3],
                [4, 5, 6],
                [7, 8]
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6, 7, 8])
                ->chunked(3)
                ->map(Types::arrayOf(), function (ListOf $items): array {
                    return $items->toArray();
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function windowed_stepLargerThanSize(): void
    {
        self::assertEquals(
            [
                [1, 2],
                [4, 5],
                [7, 8]
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6, 7, 8])
                ->windowed(2, 3)
                ->map(Types::arrayOf(), function (ListOf $items): array {
                    return $items->toArray();
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function windowed_overlappingWindows(): void
    {
        self::assertEquals(
            [
                [1, 2],
                [2, 3],
                [3, 4],
                [4, 5]
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5])
                ->windowed(2)
                ->map(Types::arrayOf(), function (ListOf $items): array {
                    return $items->toArray();
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function windowed_overlappingWindowsLargeStep(): void
    {
        self::assertEquals(
            [
                [1, 2, 3, 4, 5],
                [3, 4, 5],
                [5],
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5])
                ->windowed(5, 2, $partialWindows = true)
                ->map(Types::arrayOf(), function (ListOf $items): array {
                    return $items->toArray();
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function windowed_emptySequenceShortCircuit(): void
    {
        self::assertEquals(
            [],
            emptySequence()
                ->windowed(1)
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function windowed_failsIfSizeNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('size -1 and step -2 must be greater than zero.');
        emptySequence()->windowed(-1, -2);
    }
}
