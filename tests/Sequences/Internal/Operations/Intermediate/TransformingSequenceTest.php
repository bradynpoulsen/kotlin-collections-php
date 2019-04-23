<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\TransformingSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\TypeCheckIterator
 * @covers \BradynPoulsen\Kotlin\Types\Common\TypeAssurance
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Types\Internal\NullOverrideType
 * @covers \BradynPoulsen\Kotlin\Types\Internal\ScalarType
 * @covers \BradynPoulsen\Kotlin\Types\Internal\AbstractType
 */
class TransformingSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function map_basicTransformation(): void
    {
        self::assertEquals(
            [2, 4, 6],
            sequenceOf(Types::integer(), [1, 2, 3])
                ->map(Types::integer(), function (int $n): int {
                    return $n * 2;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function mapIndexed_basicTransformation(): void
    {
        self::assertEquals(
            [3, 3, 3],
            sequenceOf(Types::integer(), [3, 2, 1])
                ->mapIndexed(Types::integer(), function (int $index, int $n): int {
                    return $index + $n;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function mapNotNull_removesNullValues(): void
    {
        self::assertEquals(
            [2, 4, 6],
            sequenceOf(Types::integer(), [1, 2, 3, 4, 5])
                ->mapNotNull(Types::integer(), function (int $n): ?int {
                    $updated = $n + 1;
                    if ($updated % 2 === 0) {
                        return $updated;
                    }
                    return null;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function mapIndexedNotNull_keepsNonNullValues(): void
    {
        self::assertEquals(
            [1, 3, 5, 7, 9],
            sequenceOf(Types::integer(), [0, 1, 2, 3, 4])
                ->mapIndexedNotNull(Types::integer(), function (int $index, int $n): ?int {
                    $updated = $index + 1 + $n;
                    if ($updated % 2 === 1) {
                        return $updated;
                    }
                    return null;
                })
                ->toArray()
        );
    }
}
