<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use Countable;

/**
 * A generic collection of elements.
 * The type of elements is available through {@see Collection::getType()}.
 */
interface Collection extends Countable, IterableOf
{
    /**
     * Returns the size of the collection.
     */
    public function count(): int;

    /**
     * Returns `true` if the collection is empty (contains no elements), `false` otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Checks if the specified element is contained in this collection.
     *
     * @param mixed $element
     * @return bool
     *
     * @see Collection::getType()
     */
    public function contains($element): bool;

    /**
     * Checks if all elements in the specified collection are contained in this collection.
     *
     * @param Collection|mixed[] $elements
     * @return bool
     *
     * @see Collection::getType()
     */
    public function containsAll(Collection $elements): bool;

    /**
     * Checks if any elements in the specified collection are contained in this collection.
     *
     * @param Collection|mixed[] $elements
     * @return bool
     *
     * @see Collection::getType()
     */
    public function containsAny(Collection $elements): bool;

    /**
     * Returns the elements in this collection as an array.
     *
     * @return mixed[]
     *
     * @see Collection::getType()
     */
    public function toArray(): array;
}
