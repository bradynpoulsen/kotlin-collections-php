<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use function BradynPoulsen\Kotlin\Sequences\sequenceOf;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Collections\listOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\FilteringSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractLinkedIterationSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIterationSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractDisposableIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DisposableSequenceOperation
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\AbstractBaseSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class FilteringSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function filter_noopWhenEmpty(): void
    {
        self::assertEmpty(listOf(Types::integer(), [])->asSequence()->filter(function (int $x): bool {
            return $x > 0;
        })->toArray());
    }

    /**
     * @test
     */
    public function filterNot_noopWhenEmpty(): void
    {
        self::assertEmpty(listOf(Types::integer(), [])->asSequence()->filterNot(function (int $x): bool {
            return $x > 0;
        })->toArray());
    }

    /**
     * @test
     */
    public function filter_basicFiltering(): void
    {
        self::assertEquals(
            [2, 4, 6],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->filter(function (int $n): bool {
                    return $n % 2 === 0;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function filterIndexed_basicFiltering(): void
    {
        self::assertEquals(
            [2, 4],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->filterIndexed(function (int $index, int $n): bool {
                    return $n % 2 === 0 && $index < 4;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function filterIndexedNot_basicFiltering(): void
    {
        self::assertEquals(
            [5],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->filterIndexedNot(function (int $index, int $n): bool {
                    return $n % 2 === 0 || $index < 4;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function filterNot_basicFiltering(): void
    {
        self::assertEquals(
            [1, 3, 5],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->filterNot(function (int $n): bool {
                    return $n % 2 === 0;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function filter_reusable(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3])
            ->filter(function (int $x): bool {
                return $x % 2 === 1;
            });

        self::assertEquals([1, 3], $sequence->toArray());
        self::assertEquals([1, 3], $sequence->toArray());
    }

    /**
     * @test
     */
    public function filterNot_reusable(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3])
            ->filterNot(function (int $x): bool {
                return $x % 2 === 0;
            });

        self::assertEquals([1, 3], $sequence->toArray());
        self::assertEquals([1, 3], $sequence->toArray());
    }
}
