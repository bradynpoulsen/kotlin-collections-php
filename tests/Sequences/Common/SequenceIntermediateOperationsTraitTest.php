<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;

use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Collections\mutableListOf;
use function BradynPoulsen\Kotlin\Collections\mutableSetOf;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceCollectorTrait
 * @covers \BradynPoulsen\Kotlin\Types\Internal\NothingType
 * @covers ::\BradynPoulsen\Kotlin\Sequences\sequenceOf()
 * @covers ::\BradynPoulsen\Kotlin\Sequences\emptySequence()
 */
class SequenceIntermediateOperationsTraitTest extends TestCase
{
    /**
     * @test
     */
    public function drop_validatesPositiveCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        emptySequence()->drop(-1);
    }

    /**
     * @test
     */
    public function drop_zeroNoop(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3]);
        self::assertSame($sequence, $sequence->drop(0));
    }

    /**
     * @test
     */
    public function onEach_invokesMethodForEachElement(): void
    {
        $mutableList = mutableListOf(Types::integer());
        $mutableSet = mutableSetOf(Types::integer());
        sequenceOf(Types::integer(), [1, 2, 3, 1, 2, 3, 1, 2, 3])
            ->onEach([$mutableList, 'add'])
            ->onEach([$mutableSet, 'add'])
            ->toList($mutableList);

        self::assertEquals(
            [1, 1, 2, 2, 3, 3, 1, 1, 2, 2, 3, 3, 1, 1, 2, 2, 3, 3],
            $mutableList->toArray()
        );
        self::assertEquals(
            [1, 2, 3],
            $mutableSet->toArray()
        );
    }

    /**
     * @test
     */
    public function requireNoNulls_allowsNonNull(): void
    {
        self::assertEquals(
            [1, 2, 3, 4],
            sequenceOf(Types::integer(), [1, 2, 3, 4])
                ->requireNoNulls()
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function requireNoNulls_failsWhenNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        sequenceOf(Types::integerOrNull(), [1, 2, null, 3])
            ->requireNoNulls()
            ->toArray();
    }

    /**
     * @test
     */
    public function take_validatesPositiveCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        emptySequence()->take(-1);
    }

    /**
     * @test
     */
    public function take_zeroBecomesEmpty(): void
    {
        self::assertInstanceOf(EmptySequence::class, sequenceOf(Types::integer(), [1, 2])->take(0));
    }

    /**
     * @test
     */
    public function withIndex_maintainsIndexWithChains(): void
    {
        self::assertEquals(
            [0, 1, 2],
            sequenceOf(Types::integer(), [2, 3, 1])
                ->withIndex()
                ->map(Types::integer(), function (Pair $pair): int {
                    return $pair->getFirst();
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function zipWithNext_buildsPairsOfAdjacentValues(): void
    {
        self::assertEquals(
            [
                [2, 4],
                [4, 6],
                [6, 8],
                [8, 10]
            ],
            sequenceOf(Types::integer(), [2, 4, 6, 8, 10])
                ->zipWithNext()
                ->map(Types::arrayOf(), function (Pair $pair): array {
                    return [$pair->getFirst(), $pair->getSecond()];
                })
                ->toArray()
        );
    }
}
