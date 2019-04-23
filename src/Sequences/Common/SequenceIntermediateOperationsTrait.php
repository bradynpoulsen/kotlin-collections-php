<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;

use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\{ConstrainedOnceSequence,
    DistinctSequence,
    DropSequence,
    DropWhileSequence,
    FilteringSequence,
    FlatteningSequence,
    InstanceFilteringSequence,
    SortingSequence,
    TakeSequence,
    TakeWhileSequence,
    TransformingSequence,
    WindowedSequence,
    ZippingSequence};
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;

trait SequenceIntermediateOperationsTrait
{
    /**
     * @see Sequence::constrainOnce
     */
    public function constrainOnce(): Sequence
    {
        assert($this instanceof Sequence);
        return new ConstrainedOnceSequence($this);
    }

    /**
     * @see Sequence::chunked()
     */
    public function chunked(int $size): Sequence
    {
        assert($this instanceof Sequence);
        return $this->windowed($size, $size, $partialWindows = true);
    }

    /**
     * @see Sequence::distinct()
     */
    public function distinct(): Sequence
    {
        assert($this instanceof Sequence);
        return new DistinctSequence($this);
    }

    /**
     * @see Sequence::distinctBy()
     */
    public function distinctBy(callable $selector): Sequence
    {
        assert($this instanceof Sequence);
        return new DistinctSequence($this, $selector);
    }

    /**
     * @see Sequence::drop()
     */
    public function drop(int $count): Sequence
    {
        assert($this instanceof Sequence);
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive");
        }
        if ($count === 0) {
            return $this;
        }
        return new DropSequence($this, $count);
    }


    /**
     * @see Sequence::dropUntil()
     */
    public function dropUntil(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new DropWhileSequence($this, $predicate, $dropWhile = false);
    }

    /**
     * @see Sequence::dropWhile()
     */
    public function dropWhile(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new DropWhileSequence($this, $predicate, $dropWhile = true);
    }

    /**
     * @see Sequence::filter()
     */
    public function filter(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new FilteringSequence($this, $predicate, $sendWhen = true);
    }

    /**
     * @see Sequence::filterIndexed()
     */
    public function filterIndexed(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new FilteringSequence($this, $predicate, $sendWhen = true, $includeIndex = true);
    }

    /**
     * @see Sequence::filterIndexedNot()
     */
    public function filterIndexedNot(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new FilteringSequence($this, $predicate, $sendWhen = false, $includeIndex = true);
    }

    /**
     * @see Sequence::filterIsInstance()
     */
    public function filterIsInstance(Type $targetType): Sequence
    {
        assert($this instanceof Sequence);
        return InstanceFilteringSequence::filterIsInstanceUpdatedType($this, $targetType);
    }

    /**
     * @see Sequence::filterIsNotInstance()
     */
    public function filterIsNotInstance(Type $targetType): Sequence
    {
        assert($this instanceof Sequence);
        return InstanceFilteringSequence::filterIsNotInstanceUpdatedType($this, $targetType);
    }

    /**
     * @see Sequence::filterNot()
     */
    public function filterNot(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new FilteringSequence($this, $predicate, $sendWhen = false);
    }

    /**
     * @see Sequence::filterNotNull()
     */
    public function filterNotNull(): Sequence
    {
        assert($this instanceof Sequence);
        return InstanceFilteringSequence::filterNotNullUpdatedType($this);
    }

    /**
     * @see Sequence::flatMap()
     */
    public function flatMap(Type $newType, callable $transform): Sequence
    {
        assert($this instanceof Sequence);
        return $this->map(Types::instance(Sequence::class), $transform)->flatten($newType);
    }

    /**
     * @see Sequence::flatten()
     */
    public function flatten(Type $resultingType): Sequence
    {
        assert($this instanceof Sequence);
        return new FlatteningSequence($this, $resultingType);
    }

    /**
     * @see Sequence::map()
     */
    public function map(Type $newType, callable $transform): Sequence
    {
        assert($this instanceof Sequence);
        return new TransformingSequence($this, $newType, $transform);
    }

    /**
     * @see Sequence::mapIndexed()
     */
    public function mapIndexed(Type $newType, callable $transform): Sequence
    {
        assert($this instanceof Sequence);
        return new TransformingSequence($this, $newType, $transform, $includeIndex = true);
    }

    /**
     * @see Sequence::mapNotNull()
     */
    public function mapNotNull(Type $newType, callable $transform): Sequence
    {
        assert($this instanceof Sequence);
        return $this->map(Types::nullable($newType), $transform)->filterNotNull();
    }

    /**
     * @see Sequence::mapIndexedNotNull()
     */
    public function mapIndexedNotNull(Type $newType, callable $transform): Sequence
    {
        assert($this instanceof Sequence);
        return $this->mapIndexed(Types::nullable($newType), $transform)->filterNotNull();
    }

    /**
     * @see Sequence::onEach()
     */
    public function onEach(callable $action): Sequence
    {
        assert($this instanceof Sequence);
        return $this->map(
            $this->getType(),
            function ($element) use ($action) {
                call_user_func($action, $element);
                return $element;
            }
        );
    }

    /**
     * @see Sequence::requireNoNulls()
     */
    public function requireNoNulls(): Sequence
    {
        assert($this instanceof Sequence);
        return $this->mapIndexed(
            Types::notNullable($this->getType()),
            function (int $index, $element) {
                if ($element === null) {
                    throw new InvalidArgumentException("Null element found at position $index");
                }
                return $element;
            }
        );
    }

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to their
     * natural sort order.
     *
     * @effect intermediate
     * @state stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sorted(): Sequence
    {
        assert($this instanceof Sequence);
        return new SortingSequence($this, null);
    }

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the
     * natural sort order of the value returned by the given $selector.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $selector
     * @return Sequence
     */
    public function sortedBy(callable $selector): Sequence
    {
        assert($this instanceof Sequence);
        return new SortingSequence(
            $this,
            function ($a, $b) use ($selector): int {
                $aValue = $selector($a);
                $bValue = $selector($b);
                if ($aValue === $bValue) {
                    return 0;
                } elseif ($aValue < $bValue) {
                    return -1;
                }
                return 1;
            }
        );
    }

    /**
     * Returns a {@see Sequence} that yields elements of this sequence sorted according to the reverse
     * natural sort order of the value returned by the given $selector.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $selector
     * @return Sequence
     */
    public function sortedByDescending(callable $selector): Sequence
    {
        assert($this instanceof Sequence);
        return new SortingSequence($this, null, $ascending = false);
    }

    /**
     * @see Sequence::sortedDescending()
     */
    public function sortedDescending(): Sequence
    {
        assert($this instanceof Sequence);
        return new SortingSequence($this, null, $ascending = false);
    }

    /**
     * @see Sequence::sortedWith()
     */
    public function sortedWith(callable $comparator): Sequence
    {
        assert($this instanceof Sequence);
        return new SortingSequence($this, $comparator);
    }

    /**
     * @see Sequence::sortedWithDescending()
     */
    public function sortedWithDescending(callable $comparator): Sequence
    {
        assert($this instanceof Sequence);
        return new SortingSequence($this, $comparator, $ascending = false);
    }

    /**
     * @see Sequence::take()
     */
    public function take(int $count): Sequence
    {
        assert($this instanceof Sequence);
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive");
        }
        if ($count === 0) {
            return new EmptySequence($this->getType());
        }
        return new TakeSequence($this, $count);
    }

    /**
     * @see Sequence::takeUntil()
     */
    public function takeUntil(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new TakeWhileSequence($this, $predicate, $sendWhile = false);
    }

    /**
     * @see Sequence::takeWhile()
     */
    public function takeWhile(callable $predicate): Sequence
    {
        assert($this instanceof Sequence);
        return new TakeWhileSequence($this, $predicate, $sendWhile = true);
    }

    /**
     * @see Sequence::windowed()
     */
    public function windowed(int $size, int $step = 1, bool $partialWindows = false): Sequence
    {
        assert($this instanceof Sequence);
        return new WindowedSequence($this, $size, $step, $partialWindows);
    }

    /**
     * @see Sequence::withIndex()
     */
    public function withIndex(): Sequence
    {
        assert($this instanceof Sequence);
        return $this->mapIndexed(
            Types::instance(Pair::class),
            function (int $index, $element): Pair {
                return new Pair($index, $element);
            }
        );
    }

    /**
     * @see Sequence::zip()
     */
    public function zip(Sequence $other): Sequence
    {
        assert($this instanceof Sequence);
        return new ZippingSequence($this, $other);
    }

    /**
     * @see Sequence::zipWithNext()
     */
    public function zipWithNext(): Sequence
    {
        assert($this instanceof Sequence);
        return $this
            ->windowed(2, $step = 1, $partialWindows = false)
            ->map(Types::instance(Pair::class), function (ListOf $pair): Pair {
                return new Pair($pair[0], $pair[1]);
            });
    }
}
