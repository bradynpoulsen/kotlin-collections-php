<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\ZippingSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Types\Internal\StandardType
 * @covers \BradynPoulsen\Kotlin\Types\Internal\AbstractType
 * @covers \BradynPoulsen\Kotlin\Pair
 * @covers ::\BradynPoulsen\Kotlin\pair
 */
class ZippingSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function zip_createsPairsFromEachSequence(): void
    {
        self::assertEquals(
            [
                [1, "juan"],
                [2, "dude"],
                [3, "tree"],
                [4, "for"]
            ],
            sequenceOf(Types::integer(), [1, 2, 3, 4])
                ->zip(sequenceOf(Types::string(), ["juan", "dude", "tree", "for"]))
                ->map(Types::arrayOf(), function (Pair $pair): array {
                    return [$pair->getFirst(), $pair->getSecond()];
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function zip_emptySourceResultsInEmpty(): void
    {
        self::assertEmpty(
            emptySequence()
                ->zip(sequenceOf(Types::integer(), [1, 2, 3, 4]))
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function zip_emptyCompanionResultsInEmpty(): void
    {
        self::assertEmpty(
            sequenceOf(Types::integer(), [1, 2, 3, 4])
                ->zip(emptySequence())
                ->toArray()
        );
    }
}
