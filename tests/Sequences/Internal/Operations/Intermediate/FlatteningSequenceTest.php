<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use TypeError;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\FlatteningSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\EmptyIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration
 * @covers \BradynPoulsen\Kotlin\Types\Common\TypeAssurance
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Types\Internal\MixedType
 * @covers \BradynPoulsen\Kotlin\Types\Internal\InstanceType
 * @covers \BradynPoulsen\Kotlin\Types\Internal\NothingType
 * @covers \BradynPoulsen\Kotlin\Types\Internal\ScalarType
 * @covers \BradynPoulsen\Kotlin\Types\Types
 */
class FlatteningSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function flatten_emptySequenceNoop(): void
    {
        self::assertEmpty(
            emptySequence()
                ->flatten(Types::nothing())
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function flatten_singleSequence(): void
    {
        self::assertEquals(
            [1, 2, 3],
            sequenceOf(
                Types::instance(Sequence::class),
                [
                    sequenceOf(Types::integer(), [1, 2, 3])
                ]
            )
                ->flatten(Types::integer())
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function flatten_multipleElements(): void
    {
        self::assertEquals(
            [1, 2, 3, "1", "2", "3"],
            sequenceOf(Types::instance(Sequence::class), [
                sequenceOf(Types::integer(), [1, 2, 3]),
                sequenceOf(Types::string(), ["1", "2", "3"])
            ])
                ->flatten(Types::mixed())
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function flatten_multipleEmptyFollowedByValid(): void
    {
        self::assertEquals(
            [1, 2, 3],
            sequenceOf(Types::instance(Sequence::class), [
                emptySequence(),
                emptySequence(),
                sequenceOf(Types::integer(), [1, 2, 3])
            ])
                ->flatten(Types::integer())
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function flatten_incompatibleResultingSequence(): void
    {
        $this->expectException(TypeError::class);
        sequenceOf(Types::instance(Sequence::class), [
            sequenceOf(Types::integerOrNull(), [1, 2, 3])
        ])
            ->flatten(Types::stringOrNull())
            ->toArray();
    }

    /**
     * @test
     */
    public function flatten_nonSequenceSource(): void
    {
        $this->expectException(TypeError::class);
        sequenceOf(Types::integer(), [1, 2, 3])
            ->flatten(Types::stringOrNull())
            ->toArray();
    }

    /**
     * @test
     */
    public function flatMap_basicMerging(): void
    {
        self::assertEquals(
            [1, 2, 3, 4, 5, 6],
            sequenceOf(Types::integer(), [1, 4])
                ->flatMap(Types::integer(), function (int $n): Sequence {
                    return sequenceOf(Types::integer(), [$n, $n + 1, $n + 2]);
                })
                ->toArray()
        );
    }
}
