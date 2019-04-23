<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\EmptyIteration;
use EmptyIterator;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\emptySequence;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence
 */
class EmptySequenceTest extends TestCase
{
    /**
     * @test
     */
    public function emptyIterableAndIteration(): void
    {
        $sequence = emptySequence();
        assert($sequence instanceof EmptySequence);
        self::assertInstanceOf(EmptyIteration::class, $sequence->getIteration());
        self::assertInstanceOf(EmptyIterator::class, $sequence->getIterator());
    }
}
