<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Collections\Set;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Pair;
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
     * Collect all of the items in this sequence into an array.
     *
     * @effect terminal
     *
     * @param array $target If provided, the array to append the items to; otherwise a new array
     *     will be created.
     * @return array
     */
    public function toArray(array &$target = []): array;

    /**
     * Collect all of the items in this sequence into a {@see ListOf}.
     *
     * @effect terminal
     *
     * @param MutableListOf|null $target If provided, the {@see MutableListOf} to append the items
     *     to; otherwise a new {@see ListOf} will be created.
     * @return ListOf
     */
    public function toList(?MutableListOf $target = null): ListOf;

    /**
     * Collection all of the items in this sequence into a {@see Set}.
     *
     * @effect terminal
     *
     * @param MutableSet|null $target If provided, the {@see MutableSet} to append the items to;
     *     otherwise a new {@see Set} will be created.
     * @return Set
     */
    public function toSet(?MutableSet $target = null): Set;

    /*** INTERMEDIATE OPERATIONS ***/

    /**
     * Returns a wrapper {@see Sequence} that provides values of this sequence, but ensures it can
     * be iterated only one time.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function constrainOnce(): Sequence;

    /**
     * Splits this {@see Sequence} into a sequence of {@see ListOf} each not exceeding the given $size.
     * The last list in the resulting {@see Sequence} may have less items than the given $size.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param int $size the number of elements to take in each list. Must be positive.
     * @return Sequence Sequence<T> -> Sequence<ListOf<T>>
     */
    public function chunked(int $size): Sequence;

    /**
     * Returns a {@see Sequence} containing only distinct elements from the given sequence.
     * The elements in the resulting sequence are in the same order as they were in the source sequence.
     *
     * @effect intermediate
     * @state stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function distinct(): Sequence;

    /**
     * Returns a {@see Sequence} containing only elements from this sequence having distinct keys
     * returned by the given $selector function.
     *
     * The elements in the resulting sequence are in the same order as they were in the source sequence.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $selector (T) -> K
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function distinctBy(callable $selector): Sequence;

    /**
     * Returns a {@see Sequence} containing all elements except the first $count elements.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param int $count
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function drop(int $count): Sequence;

    /**
     * Returns a {@see Sequence} containing all elements except first elements that do not satisfy
     * the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function dropUntil(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} containing all elements except first elements that satisfy the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function dropWhile(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} with only <T> elements matching the given $predicate.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filter(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} containing only elements matching the given $predicate.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (int index, T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filterIndexed(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} containing only elements NOT matching the given $predicate.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (int index, T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filterIndexedNot(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} containing only elements that are contained in the specified $targetType.
     *
     * @param Type $targetType Type<R>
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function filterIsInstance(Type $targetType): Sequence;

    /**
     * Returns a {@see Sequence} containing only elements that are NOT contained in the specified $targetType.
     *
     * @param Type $targetType Type<R>
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filterIsNotInstance(Type $targetType): Sequence;

    /**
     * Returns a {@see Sequence} with only elements NOT matching the given $predicate.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filterNot(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} containing all elements that are not `null`.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<?T> -> Sequence<T>
     */
    public function filterNotNull(): Sequence;

    /**
     * Returns a {@see Sequence} of all elements from {@see Sequence} results of the given $transform
     * function being invoked on each element of this sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Type $newType Type<R>
     * @param callable $transform (T) -> Sequence<R>
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function flatMap(Type $newType, callable $transform): Sequence;

    /**
     * Returns a {@see Sequence} of all elements from sequences contained in this sequence.
     * A {@see TypeError} is thrown during iteration if a contained sequence is found not to be contained in
     * the specified $resultingType.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Type $resultingType Type<T>
     * @return Sequence Sequence<Sequence<R>> -> Sequence<T>
     *
     * @throws InvalidArgumentException if this sequence type does not contain {@see Sequence}s.
     */
    public function flatten(Type $resultingType): Sequence;

    /**
     * Returns a {@see Sequence} containing the results of applying the given $transform function
     * to each element in the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Type $newType Type<R>
     * @param callable $transform (T) -> R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function map(Type $newType, callable $transform): Sequence;

    /**
     * Returns a {@see Sequence} containing the results of applying the given $transform function
     * to each element in the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Type $newType Type<R>
     * @param callable $transform (int index, T) -> R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function mapIndexed(Type $newType, callable $transform): Sequence;

    /**
     * Returns a {@see Sequence} containing only the non-null results of applying the given $transform
     * function to each element in the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Type $newType Type<R>
     * @param callable $transform (T) -> ?R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function mapNotNull(Type $newType, callable $transform): Sequence;

    /**
     * Returns a {@see Sequence} containing only the non-null results of applying the given $transform
     * function to each element in the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Type $newType Type<R>
     * @param callable $transform (int index, T) -> ?R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function mapIndexedNotNull(Type $newType, callable $transform): Sequence;

    /**
     * Returns a {@see Sequence} which performs the given $action on each element of the original
     * sequence as they pass through it.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $action (T) -> void
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function onEach(callable $action): Sequence;

    /**
     * Returns a {@see Sequence} containing all the non-null elements, throwing an
     * {@see InvalidArgumentException} if a null element is found.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<?T> -> Sequence<T>
     */
    public function requireNoNulls(): Sequence;

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to their
     * sort order.
     *
     * @effect intermediate
     * @state stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sorted(): Sequence;

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the
     * sort order of the value returned by the given $selector.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $selector (T) -> R
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedBy(callable $selector): Sequence;

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the reverse
     * sort order of the value returned by the given $selector.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $selector (T) -> R
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedByDescending(callable $selector): Sequence;

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to their
     * reverse natural sort order.
     *
     * @effect intermediate
     * @state stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedDescending(): Sequence;

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the specified
     * comparator.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $comparator (T $a, T $b) -> int that is less than, equal to, or greater than
     *     zero if $a is consider respectively less than, equal to, or greater than $b.
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedWith(callable $comparator): Sequence;

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the specified
     * comparator reversed.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $comparator (T $a, T $b) -> int that is less than, equal to, or greater than
     *     zero if $a is consider respectively less than, equal to, or greater than $b.
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedWithDescending(callable $comparator): Sequence;

    /**
     * Returns a {@see Sequence} containing only the first $count elements.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param int $count
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function take(int $count): Sequence;

    /**
     * Returns a {@see Sequence} containing first elements not satisfying the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function takeUntil(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} containing first elements satisfying the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function takeWhile(callable $predicate): Sequence;

    /**
     * Returns a {@see Sequence} of {@see ListOf} snapshots of the window of the given $size sliding
     * along this sequence with the given $step.
     *
     * Several last lists may have less elements than the given $size.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param int $size the number of elements to take each window. Must be positive.
     * @param int $step the number of elements to move the window forward by on each step. Must be positive.
     * @param bool $partialWindows controls whether or not to keep partial windows in the end, if any.
     * @return Sequence Sequence<T> -> Sequence<ListOf<T>>
     */
    public function windowed(int $size, int $step = 1, bool $partialWindows = false): Sequence;

    /**
     * Returns a {@see Sequence} of {@see Pair} where the first value is the index and the second value
     * is the element from the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<T> -> Sequence<Pair<int, T>>
     */
    public function withIndex(): Sequence;

    /**
     * Returns a {@see Sequence} of {@see Pair} built from the elements of this sequence and the given
     * $other sequence with the same index.
     * The resulting {@see Sequence} ends as soon as the shortest input sequence ends.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Sequence $other Sequence<R>
     * @return Sequence Sequence<T> -> Sequence<Pair<T, R>>
     */
    public function zip(Sequence $other): Sequence;

    /**
     * Returns a {@see Sequence} of {@see Pair} of each two adjacent elements in this sequence.
     * The resulting {@see Sequence} is empty if this sequence contains less than two elements.
     *
     * @effect intermediate
     * @state stateful
     *
     * @return Sequence Sequence<T> -> Sequence<Pair<T, T>>
     */
    public function zipWithNext(): Sequence;
}
