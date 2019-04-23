<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\LinkedSequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\TakeSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\EmptyIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class TakeSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function take_onlyTakesAvailableItems(): void
    {
        self::assertEquals([1, 2, 3], sequenceOf(Types::integer(), [1, 2, 3])->take(10)->toArray());
    }

    /**
     * @test
     */
    public function take_thenInvalidNegativeTake(): void
    {
        $this->expectException(InvalidArgumentException::class);
        emptySequence()->take(1)->take(-1);
    }

    /**
     * @test
     */
    public function take_thenZeroTake(): void
    {
        self::assertInstanceOf(
            EmptySequence::class,
            sequenceOf(Types::integer(), [1, 2])
                ->take(1)
                ->take(0)
        );
    }

    /**
     * @test
     */
    public function take_thenTakeLargerAmount(): void
    {
        $sequence = emptySequence()->take(10);
        self::assertSame($sequence, $sequence->take(20));
    }

    /**
     * @test
     */
    public function take_thenTakeSmallerUnwraps(): void
    {
        $sequence = emptySequence()->take(2);
        $smaller = $sequence->take(1);
        assert($sequence instanceof LinkedSequence);
        assert($smaller instanceof LinkedSequence);
        self::assertSame($sequence->getPrevious(), $smaller->getPrevious());
    }

    /**
     * @test
     */
    public function take_thenLargerDrop(): void
    {
        self::assertInstanceOf(EmptySequence::class, emptySequence()->take(10)->drop(10));
    }

    /**
     * @test
     */
    public function take_thenInvalidNegativeDrop(): void
    {
        $this->expectException(InvalidArgumentException::class);
        emptySequence()->take(10)->drop(-1);
    }
}
