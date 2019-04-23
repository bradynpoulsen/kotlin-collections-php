<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DistinctSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DisposableSequenceOperation
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractDisposableIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class DistinctSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function distinct_discardsDuplicateObjects(): void
    {
        $emptySequence = emptySequence();

        $sequence = sequenceOf(
            Types::instance(Sequence::class),
            [
                $emptySequence,
                $emptySequence
            ]
        )->distinct();

        self::assertSame([$emptySequence], $sequence->toArray());
    }

    /**
     * @test
     */
    public function distinct_distinguishesBetweenScalarTypes(): void
    {
        self::assertSame(
            [
                1,
                "1",
                1.0,
                true
            ],
            sequenceOf(
                Types::mixed(),
                [
                    1,
                    "1",
                    1.0,
                    true
                ]
            )->distinct()->toArray()
        );
    }

    /**
     * @test
     */
    public function distinctBy_usesTheProvidedSelector(): void
    {
        $first = $this->createWrappingObject('2019-02-28');
        $second = $this->createWrappingObject('2019-03-02');
        $third = $this->createWrappingObject('2019-03-02');
        self::assertSame(
            [$first, $second],
            sequenceOf(Types::object(), [$first, $second, $third])
                ->distinctBy(function (object $wrapper): string {
                    return $wrapper->value;
                })
                ->toArray()
        );
    }

    private function createWrappingObject($value): object
    {
        return new class($value) {
            public $value;

            public function __construct($value)
            {
                $this->value = $value;
            }
        };
    }
}
