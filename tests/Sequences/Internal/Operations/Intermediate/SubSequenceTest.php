<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\SubSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\DropSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\TakeSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class SubSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_validatesPositiveStartIndex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SubSequence(emptySequence(), -1, 0);
    }

    /**
     * @test
     */
    public function constructor_validatesPositiveEndIndex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SubSequence(emptySequence(), 0, -1);
    }

    /**
     * @test
     */
    public function constructor_validatesStartIndexBeforeEndIndex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SubSequence(emptySequence(), 2, 1);
    }

    /**
     * @test
     */
    public function drop_movesUpStartIndex(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->take(3)->drop(1);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(2, $subSequence->getEndIndex());

        $additionalDrop = $subSequence->drop(1);
        assert($additionalDrop instanceof SubSequence);
        self::assertEquals(2, $additionalDrop->getStartIndex());
        self::assertEquals([3], $additionalDrop->toArray());
    }

    /**
     * @test
     */
    public function drop_validatesPositiveCount(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        $this->expectException(InvalidArgumentException::class);
        $subSequence->drop(-1);
    }

    /**
     * @test
     */
    public function drop_zeroCountNoop(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        self::assertSame($subSequence, $subSequence->drop(0));
    }

    /**
     * @test
     */
    public function drop_allElementsResultsInEmptySequence(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        self::assertInstanceOf(EmptySequence::class, $subSequence->drop(3));
    }

    /**
     * @test
     */
    public function take_fewerElementsUnwraps(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        $updatedSequence = $subSequence->take(1);
        assert($updatedSequence instanceof SubSequence);
        self::assertEquals(1, $updatedSequence->getStartIndex());
        self::assertEquals(1, $updatedSequence->getEndIndex());
    }

    /**
     * @test
     */
    public function take_validatesPositiveCount(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        $this->expectException(InvalidArgumentException::class);
        $subSequence->take(-1);
    }

    /**
     * @test
     */
    public function take_zeroCountResultsInEmpty(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        self::assertInstanceOf(EmptySequence::class, $subSequence->take(0));
    }

    /**
     * @test
     */
    public function take_greaterThanAvailableResultsInNoop(): void
    {
        $subSequence = sequenceOf(Types::integer(), [1, 2, 3, 4, 5, 6])->drop(1)->take(3);
        assert($subSequence instanceof SubSequence);
        self::assertEquals(1, $subSequence->getStartIndex());
        self::assertEquals(3, $subSequence->getEndIndex());

        self::assertSame($subSequence, $subSequence->take(3));
        self::assertSame($subSequence, $subSequence->take(4));
    }
}
