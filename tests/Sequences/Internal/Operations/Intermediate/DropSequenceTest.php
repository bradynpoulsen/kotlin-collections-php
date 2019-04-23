<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IterableOfSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\LinkedSequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DropSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DisposableSequenceOperation
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class DropSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function drop_skipsTheFirstNElements(): void
    {
        self::assertEquals([4, 5, 6], sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(3)->toArray());
    }

    /**
     * @test
     */
    public function drop_doesNotTryToDropMoreElementsThanAvailable(): void
    {
        self::assertEquals([], sequenceOf(Types::integer(), [1, 2, 3])->drop(4)->toArray());
    }

    /**
     * @test
     */
    public function drop_simplifiesExtendingDropWithSingleSequence(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3])->drop(1)->drop(1);
        self::assertInstanceOf(DropSequence::class, $sequence);
        assert($sequence instanceof LinkedSequence);
        self::assertInstanceOf(IterableOfSequence::class, $sequence->getPrevious());
    }

    /**
     * @test
     */
    public function drop_followByDropOfZeroNoops(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2, 3])->drop(1);
        self::assertSame($sequence, $sequence->drop(0));
    }

    /**
     * @test
     */
    public function constructor_count_mustBeGreaterThanZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("count -1 must be positive.");
        new DropSequence(emptySequence(), -1);
    }

    /**
     * @test
     */
    public function drop_count_mustBePositive(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("count -1 must be positive.");
        (new DropSequence(emptySequence(), 0))->drop(-1);
    }

    /**
     * @test
     */
    public function take_count_mustBePositive(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("count -1 must be positive.");
        (new DropSequence(emptySequence(), 0))->take(-1);
    }

    /**
     * @test
     */
    public function take_zeroBecomesEmpty(): void
    {
        self::assertInstanceOf(EmptySequence::class, (new DropSequence(emptySequence(), 0))->take(0));
    }
}
