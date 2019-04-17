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
 * Operations must be classified into groups of state requirements and effect.
 *
 * State Requirements:
 *   - @state stateless - operations which require no state and process each element independently.
 *   - @state stateful - operations which require an amount of state, usually proportional to number of elements
 *
 * Effect:
 *   - @effect intermediate - operations that return another sequence, which process each element lazily
 *   - @effect terminal - operations that consume the sequence to return a non-sequence result
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

    /**
     * Returns a wrapper sequence that provides values of this sequence, but ensures it can be iterated only one time.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence
     */
    public function constrainOnce(): Sequence;
}
