<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use BradynPoulsen\Kotlin\Collections\MutableCollection;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorFactorySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorSequence;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
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
     * @param Type $type Type<T>
     * @param callable $factory () -> Iterator<T>
     * @return Sequence Sequence<T>
     */
    public static function iteratorFactory(Type $type, callable $factory): Sequence
    {
        return new IteratorFactorySequence($type, $factory);
    }

    /**
     * Create a sequence from an {@see IteratorAggregate}.
     *
     * @param Type $type Type<T>
     * @param IteratorAggregate $aggregate IteratorAggregate<T>
     * @return Sequence Sequence<T>
     */
    public static function iteratorAggregate(Type $type, IteratorAggregate $aggregate): Sequence
    {
        return new IteratorFactorySequence($type, [$aggregate, 'getIterator']);
    }

    /**
     * Create a sequence from an {@see Iterator} that can only be consumed once.
     *
     * @param Type $type Type<T>
     * @param Iterator $iterator Iterator<T>
     * @return Sequence Sequence<T>
     */
    public static function iterator(Type $type, Iterator $iterator): Sequence
    {
        return IteratorSequence::create($type, $iterator);
    }

    /**
     * Collect all of the elements in the provided $sequence into the provided $target.
     * The {@see Type} of the given $target must contain the type of the given $source.
     *
     * @param Sequence $source Sequence<T>
     * @param MutableCollection $target MutableCollection<R>
     */
    public static function collectTo(Sequence $source, MutableCollection $target): void
    {
        TypeAssurance::ensureContainedArgumentType($target->getType(), 1, $source->getType());
        foreach ($source as $element) {
            $target->add($element);
        }
    }
}
