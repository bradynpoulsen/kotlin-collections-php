<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\Map;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\Collections\MutableMap;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Collections\Set;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use BradynPoulsen\Kotlin\UnsupportedOperationException;
use IteratorAggregate;
use Traversable;
use TypeError;

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

    /*** TERMINAL COLLECTORS ***/

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
     * Collect all of the items in this sequence into a {@see Set}.
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
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the given
     * $comparator.
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
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the given
     * $comparator reversed.
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

    /*** TERMINAL OPERATIONS ***/

    /**
     * Returns `true` if all contained elements match the given $predicate.
     * Returns `true` if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return bool Sequence<T> -> bool
     */
    public function all(callable $predicate): bool;

    /**
     * Returns `true` if any contained elements match the given $predicate.
     * Returns `false` if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return bool Sequence<T> -> bool
     */
    public function any(callable $predicate): bool;

    /**
     * Returns a {@see Map} containing key-value pairs created by the given $transform function applied
     * to each element of this sequence.
     *
     * @effect terminal
     *
     * @param Type $keyType Type<K>
     * @param Type $valueType Type<V>
     * @param callable $transform (T) -> Pair<K, V>
     * @return Map Sequence<T> -> Map<K, V>
     */
    public function associate(Type $keyType, Type $valueType, callable $transform): Map;

    /**
     * Returns a {@see Map} containing the elements from this sequence indexed by the key returned from
     * the given $keySelector.
     *
     * @effect terminal
     *
     * @param Type $keyType Type<K>
     * @param callable $keySelector (T) -> K
     * @return Map Sequence<T> -> Map<K, T>
     */
    public function associateBy(Type $keyType, callable $keySelector): Map;

    /**
     * Populates the given $destination with key-value pairs created by the given $transform function
     * applied to each element of this sequence.
     *
     * @effect terminal
     *
     * @param MutableMap $destination MutableMap<K, V>
     * @param callable $transform (T) -> Pair<K, V>
     * @return MutableMap Sequence<T> -> MutableMap<K, V>
     */
    public function associateTo(MutableMap $destination, callable $transform): MutableMap;

    /**
     * Populates the given $destination with key-value pairs, where the key is provided by the given
     * $keySelector function applied to each element and the value is the element itself.
     *
     * @effect terminal
     *
     * @param MutableMap $destination MutableMap<K, T>
     * @param callable $keySelector (T) -> K
     * @return MutableMap Sequence<T> -> MutableMap<K, T>
     */
    public function associateByTo(MutableMap $destination, callable $keySelector): MutableMap;

    /**
     * Returns a {@see Map} containing the elements of this sequence indexed by each element and values
     * produced by the $valueSelector function.
     *
     * @effect terminal
     *
     * @param Type $valueType Type<V>
     * @param callable $valueSelector (T) -> V
     * @return Map Sequence<T> -> Map<T, V>
     */
    public function associateWith(Type $valueType, callable $valueSelector): Map;

    /**
     * Populates the given $destination with key-value pairs, where the key is the element itself
     * and the value is provided by the given $valueSelector function applied to each element.
     *
     * @effect terminal
     *
     * @param MutableMap $destination Map<T, V>
     * @param callable $valueSelector (T) -> V
     * @return MutableMap Sequence<T> -> MutableMap<T, V>
     */
    public function associateWithTo(MutableMap $destination, callable $valueSelector): MutableMap;

    /**
     * Returns an average of all values contained in this sequence.
     *
     * A {@see TypeError} is thrown if this sequence does not contain {@see Types::number()} elements.
     *
     * @effect terminal
     *
     * @return float
     */
    public function average(): float;

    /**
     * Returns an average of all values produced by the given $selector applied to each element
     * in this sequence.
     *
     * A {@see TypeError} is thrown if $selector does not return a {@see Types::number()} value.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> int|float
     * @return float
     */
    public function averageBy(callable $selector): float;

    /**
     * Returns the number of elements in this sequence.
     *
     * @effect terminal
     *
     * @return int Sequence<T> -> int
     */
    public function count(): int;

    /**
     * Returns the number of elements in this sequence that match the given $predicate.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return int Sequence<T> -> int
     */
    public function countBy(callable $predicate): int;

    /**
     * Returns the first element.
     *
     * A {@see NoSuchElementException} is thrown if this sequence is empty.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T
     */
    public function first();

    /**
     * Returns the first element that matches the given $predicate.
     *
     * A {@see NoSuchElementException} is thrown if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     */
    public function firstBy(callable $predicate);

    /**
     * Returns the first element that matches the given $predicate, or `null` if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return mixed Sequence<T> -> T?
     */
    public function firstByOrNull(callable $predicate);

    /**
     * Returns the first element, or `null` if this sequence is empty.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T?
     */
    public function firstOrNull();

    /**
     * Accumulates value starting with the given $initial and applying $calculator on each element.
     * Returns the given $initial value if this sequence is empty.
     *
     * @effect terminal
     *
     * @param mixed $initial R
     * @param callable $calculator (R $accumulated, T $element) -> R
     * @return mixed Sequence<T> -> R
     */
    public function fold($initial, callable $calculator);

    /**
     * Accumulates value starting with the given $initial and apply $calculator on each element and its index.
     * Returns the given $initial value if this sequence is empty.
     *
     * @effect terminal
     *
     * @param mixed $initial R
     * @param callable $calculator (R $accumulated, int $index, T $element) -> R
     * @return mixed Sequence<T> -> R
     */
    public function foldIndexed($initial, callable $calculator);

    /**
     * Returns `true` if this sequence contains no elements.
     *
     * @effect terminal
     *
     * @return bool Sequence<T> -> bool
     */
    public function isEmpty(): bool;

    /**
     * Returns `false` if this sequence contains no elements.
     *
     * @effect terminal
     *
     * @return bool Sequence<T> -> bool
     */
    public function isNotEmpty(): bool;

    /**
     * Creates a string from all the elements separated by the given $separator and using the given
     * $prefix and $postfix.
     *
     * @effect terminal
     *
     * @param string $separator The value to separate each element by
     * @param string $prefix The value to prefix the resulting string with.
     * @param string $postfix The value to postfix the resulting string with.
     * @param int $limit If greater than -1, the maximum number of elements to append before representing
     *     all remaining values with a single given $truncated placeholder.
     * @param string $truncated The value to show that elements were truncated for exceeding the given $limit.
     * @param callable|null $transform (T) -> string Transforming function to prepare each element.
     * @return string
     */
    public function joinToString(
        string $separator = ", ",
        string $prefix = "",
        string $postfix = "",
        int $limit = -1,
        string $truncated = "...",
        ?callable $transform = null
    ): string;

    /**
     * Returns the last element.
     *
     * A {@see NoSuchElementException} is thrown if this sequence is empty.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T
     */
    public function last();

    /**
     * Returns the last element that matches the given $predicate.
     *
     * A {@see NoSuchElementException} is thrown if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     */
    public function lastBy(callable $predicate);

    /**
     * Returns the last element that matches the given $predicate, or `null` if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return mixed Sequence<T> -> T?
     */
    public function lastByOrNull(callable $predicate);

    /**
     * Returns the last element, or `null` if this sequence is empty.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T?
     */
    public function lastOrNull();

    /**
     * Returns the maximum of all values contained in this sequence.
     * Returns `null` if there are no elements in this sequence.
     *
     * A {@see TypeError} is thrown if this sequence does not contain {@see Types::number()} elements.
     *
     * @effect terminal
     *
     * @return null|int|float Sequence<T> -> T?
     */
    public function max();

    /**
     * Returns the first element having the largest value of the given $selector.
     * Returns `null` if there are no elements in this sequence.
     *
     * A {@see TypeError} is thrown if $selector does not return a {@see Types::number()} value.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> number
     * @return mixed Sequence<T> -> T?
     */
    public function maxBy(callable $selector);

    /**
     * Returns the first element having the largest value according to the provided $comparator.
     * Returns `null` if there are no elements in this sequence.
     *
     * @effect terminal
     *
     * @param callable $comparator (T $a, T $b) -> int that is less than, equal to, or greater than
     *     zero if $a is consider respectively less than, equal to, or greater than $b.
     * @return mixed Sequence<T> -> T?
     */
    public function maxWith(callable $comparator);

    /**
     * Returns the smallest of all values contained in this sequence.
     * Returns `null` if there are no elements in this sequence.
     *
     * A {@see TypeError} is thrown if this sequence does not contain {@see Types::number()} elements.
     *
     * @effect terminal
     *
     * @return null|int|float Sequence<T> -> T?
     */
    public function min();

    /**
     * Returns the first element having the smallest value of the given $selector.
     * Returns `null` if there are no elements in this sequence.
     *
     * A {@see TypeError} is thrown if $selector does not return a {@see Types::number()} value.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> number
     * @return mixed Sequence<T> -> T?
     */
    public function minBy(callable $selector);

    /**
     * Returns the first element having the smallest value according to the provided $comparator.
     * Returns `null` if there are no elements in this sequence.
     *
     * @effect terminal
     *
     * @param callable $comparator (T $a, T $b) -> int that is less than, equal to, or greater than
     *     zero if $a is consider respectively less than, equal to, or greater than $b.
     * @return mixed Sequence<T> -> T?
     */
    public function minWith(callable $comparator);

    /**
     * Returns `true` if no elements match the given $predicate.
     * Returns `true` if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return bool Sequence<T> -> bool
     */
    public function none(callable $predicate): bool;

    /**
     * Splits this sequence into a {@see Pair} of {@see ListOf}, where {@see Pair::getFirst()} contains
     * elements that the given $predicate returns `true` and {@see Pair::getSecond()} contains elements
     * that return `false`.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return Pair Pair<ListOf<T>, ListOf<T>>
     */
    public function partition(callable $predicate): Pair;

    /**
     * Accumulates value starting with the first element and applying $calculator on each element.
     * An {@see UnsupportedOperationException} is thrown if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $calculator (S $accumulated, T $element) -> S
     * @return mixed Sequence<T : S> -> S
     */
    public function reduce(callable $calculator);

    /**
     * Accumulates value starting with the first element and applying $calculator on each element with its index.
     * An {@see UnsupportedOperationException} is thrown if this sequence is empty.
     *
     * @effect terminal
     *
     * @param callable $calculator (S $accumulated, int $index, T $element) -> S
     * @return mixed Sequence<T : S> -> S
     */
    public function reduceIndexed(callable $calculator);

    /**
     * Returns the single element in this sequence.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T
     * @throws NoSuchElementException if this sequence is empty.
     * @throws InvalidArgumentException if there was more than one element in this sequence.
     */
    public function single();

    /**
     * Returns the single element in this sequence matching the given $predicate.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     * @throws NoSuchElementException if this sequence is empty.
     * @throws InvalidArgumentException if there was more than one element that matched the given $predicate.
     */
    public function singleBy(callable $predicate);

    /**
     * Returns the single element in this sequence matching the given $predicate, or `null` if this
     * sequence is empty or contained more than one element.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return mixed Sequence<T> -> T?
     */
    public function singleByOrNull(callable $predicate);

    /**
     * Returns the single element in this sequence, or `null` if this sequence is empty or contained
     * more than one element.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T?
     */
    public function singleOrNull();

    /**
     * Returns a sum of all values contained in this sequence.
     *
     * A {@see TypeError} is thrown if this sequence does not contain {@see Types::integer()} elements.
     *
     * @effect terminal
     *
     * @return int Sequence<int> -> int
     */
    public function sum(): int;

    /**
     * Returns a sum of all values produced by the given $selector applied to each element
     * in this sequence.
     *
     * A {@see TypeError} is thrown if $selector does not return an {@see Types::integer()} value.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> int
     * @return int Sequence<T> -> int
     */
    public function sumBy(callable $selector): int;

    /**
     * Returns a sum of all values produced by the given $selector applied to each element
     * in this sequence.
     *
     * A {@see TypeError} is thrown if $selector does not return an {@see Types::float()} value.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> float
     * @return float Sequence<T> -> float
     */
    public function sumByFloat(callable $selector): float;

    /**
     * Returns a sum of all values contained in this sequence.
     *
     * A {@see TypeError} is thrown if this sequence does not contain {@see Types::float()} elements.
     *
     * @effect terminal
     *
     * @return float Sequence<float> -> float
     */
    public function sumFloat(): float;

    /**
     * Return a {@see Pair} of {@see ListOf}, where {@see Pair::getFirst()} contains the first value
     * each contained pair and {@see Pair::getSecond()} contains the second value.
     *
     * A {@see TypeError} is thrown if this sequence does not contain {@see Pair} elements.
     *
     * @effect terminal
     *
     * @param Type $firstType Type<T>
     * @param Type $secondType Type<R>
     * @return Pair Sequence<Pair<T, R>> -> Pair<List<T>, List<R>>
     */
    public function unzip(Type $firstType, Type $secondType): Pair;
}
