<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\TakeWhileSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class TakeWhileSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function takeWhile_basicCheck(): void
    {
        self::assertEquals(
            [1, 2, 3],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])
                ->takeWhile(function (int $n): bool {
                    return $n < 4;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function takeUntil_basicCheck(): void
    {
        self::assertEquals(
            [1, 2],
            sequenceOf(Types::integer(), [1, 2, 3, 4])
                ->takeUntil(function (int $n): bool {
                    return $n === 3;
                })
                ->toArray()
        );
    }
}
