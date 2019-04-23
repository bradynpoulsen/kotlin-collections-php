<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\InstanceFilteringSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 */
class InstanceFilteringSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function filterIsInstance_restrictedType(): void
    {
        $sequence = sequenceOf(Types::mixed(), [1, "1", 1.0, true, 2, "2", 2.0, 3, "3", 3.0, null])
            ->filterIsInstance(Types::integer());

        self::assertEquals("integer", $sequence->getType()->getName());
        self::assertEquals([1, 2, 3], $sequence->toArray());
    }

    /**
     * @test
     */
    public function filterIsInstance_typeNotContainedYieldsEmpty(): void
    {
        self::assertInstanceOf(
            EmptySequence::class,
            sequenceOf(Types::integer(), [1, 2])
                ->filterIsInstance(Types::string())
        );
    }

    /**
     * @test
     */
    public function filterIsNotInstance_maintainsType(): void
    {
        $sequence = sequenceOf(Types::mixed(), [1, "1", 1.0, true, 2, "2", 2.0, 3, "3", 3.0, null])
            ->filterIsNotInstance(Types::integer());

        self::assertEquals("mixed", $sequence->getType()->getName());
        self::assertEquals(["1", 1.0, true, "2", 2.0, "3", 3.0, null], $sequence->toArray());
    }

    /**
     * @test
     */
    public function filterIsNotInstance_typeNotContainedNoops(): void
    {
        $sequence = sequenceOf(Types::integer(), [1, 2]);
        self::assertSame($sequence, $sequence->filterIsNotInstance(Types::string()));
    }

    /**
     * @test
     */
    public function filterNotNull_restrictsType(): void
    {
        $sequence = sequenceOf(Types::integerOrNull(), [1, null, 2, null, 3, null])
            ->filterNotNull();

        self::assertFalse($sequence->getType()->acceptsNull());
        self::assertEquals([1, 2, 3], $sequence->toArray());
    }
}
