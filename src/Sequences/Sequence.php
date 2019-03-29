<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Types\Type;
use IteratorAggregate;
use Traversable;

/**
 * A sequence of values that can be iterated over. The values are evaluated lazily, and the sequence
 * is potentially infinite.
 *
 * Sequences can be iterated multiple times, unless implementations constrain themselves to be iterated
 * only once. Operations, like map, filter, etc, generally preserved this constraint, and must be
 * documented if it doesn't.
 *
 * The type of elements is available through {@see Sequence::getType()} and is covariant.
 */
interface Sequence extends IteratorAggregate
{
    /**
     * Get the type name allowed for values of this sequence.
     * A {@see TypeError} will be thrown whenever a value is provided that does not qualify as an
     * instance of this type.
     */
    public function getType(): Type;

    /**
     * Returns an iterator over the values in this sequence.
     *
     * @throws InvalidStateException if the sequence is constrained to be iterated only once and
     *     {@see Sequence::getIterator()} is invoked a second time.
     */
    public function getIterator(): Traversable;
}
