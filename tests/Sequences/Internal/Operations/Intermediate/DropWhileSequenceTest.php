<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DropWhileSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class DropWhileSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function dropWhile_basicCompare(): void
    {
        self::assertEquals(
            [3, 4],
            sequenceOf(Types::integer(), [1, 2, 3, 4])
                ->dropWhile(function (int $n): bool {
                    return $n < 3;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function dropUntil_basicCompare(): void
    {
        self::assertEquals(
            [4],
            sequenceOf(Types::integer(), [1, 2, 3, 4])
                ->dropUntil(function (int $n): bool {
                    return $n > 3;
                })
                ->toArray()
        );
    }
}
