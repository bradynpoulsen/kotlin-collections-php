<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use BradynPoulsen\Kotlin\Sequences\Internal\IteratorFactorySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\IteratorSequence;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;
use IteratorAggregate;

/**
 * Sequence factory for common PHP types.
 */
final class Sequences
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Create a sequence from a factory method that accepts no arguments and returns an {@see Iterator}.
     *
     * @param Type $type
     * @param callable $factory () -> Iterator
     * @return Sequence
     */
    public static function iteratorFactory(Type $type, callable $factory): Sequence
    {
        return new IteratorFactorySequence($type, $factory);
    }

    /**
     * Create a sequence from an {@see IteratorAggregate}.
     *
     * @param Type $type
     * @param IteratorAggregate $aggregate
     * @return Sequence
     */
    public static function iteratorAggregate(Type $type, IteratorAggregate $aggregate): Sequence
    {
        return new IteratorFactorySequence($type, [$aggregate, 'getIterator']);
    }

    /**
     * Create a sequence from an {@see Iterator} that can only be consumed once.
     *
     * @param Type $type
     * @param Iterator $iterator
     * @return Sequence
     */
    public static function iterator(Type $type, Iterator $iterator): Sequence
    {
        return IteratorSequence::create($type, $iterator);
    }
}
