<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Common\SequenceCommonTrait;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use EmptyIterator;
use PHPUnit\Framework\TestCase;
use Traversable;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration
 */
class SequenceIterationTest extends TestCase
{
    /**
     * @test
     */
    public function fromSequence_usesIteratorWhenNotIterationSequence(): void
    {
        $sequence = new class implements Sequence {
            use SequenceCommonTrait;

            public function getType(): Type
            {
                return Types::nothing();
            }

            public function getIterator(): Traversable
            {
                return new EmptyIterator();
            }
        };

        $iteration = SequenceIteration::fromSequence($sequence);
        self::assertInstanceOf(IteratorIteration::class, $iteration);
    }
}
