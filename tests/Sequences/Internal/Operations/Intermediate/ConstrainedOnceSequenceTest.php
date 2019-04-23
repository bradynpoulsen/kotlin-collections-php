<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Sequences\Common\SequenceCommonTrait;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use Traversable;
use function BradynPoulsen\Kotlin\Collections\unsafeListOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\ConstrainedOnceSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractLinkedIterationSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\IterableOfSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\AbstractBaseSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class ConstrainedOnceSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function constrainOnce_failsIfTerminatedTwice(): void
    {
        $constrained = unsafeListOf([1, 2, 3])->asSequence()->constrainOnce();

        self::assertEquals([1, 2, 3], $constrained->toArray());

        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage("This sequence can only be consumed once.");
        $constrained->toList();
    }

    /**
     * @test
     */
    public function constrainOnce_failedIfChainIteratedTwice(): void
    {
        $constrained = unsafeListOf([1, 2, 3])
            ->asSequence()
            ->constrainOnce()
            ->map(Types::integer(), function (int $n): int {
                return $n * 2;
            });

        self::assertEquals([2, 4, 6], $constrained->toArray());

        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage("This sequence can only be consumed once.");
        $constrained->toList();
    }

    /**
     * @test
     */
    public function constrainOnce_failsIfGeneratedTwice(): void
    {
        $constrained = (new class implements Sequence {
            use SequenceCommonTrait;

            public function getType(): Type
            {
                return Types::integer();
            }

            public function getIterator(): Traversable
            {
                yield 1;
                yield 2;
                yield 3;
            }
        })
            ->constrainOnce()
            ->map(Types::integer(), function (int $n): int {
                return $n * 2;
            });

        self::assertEquals([2, 4, 6], $constrained->toArray());

        $this->expectException(InvalidStateException::class);
        $this->expectExceptionMessage("This sequence can only be consumed once.");
        $constrained->toList();
    }

    /**
     * @test
     */
    public function constrainOnce_noopWhenConstrainedTwice(): void
    {
        $contrained = unsafeListOf([1, 2, 3])->asSequence()->constrainOnce();

        self::assertSame($contrained, $contrained->constrainOnce());
    }
}
