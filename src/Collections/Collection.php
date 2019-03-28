<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

/**
 * A generic collection of elements.
 * The type of elements is available through {@see Collection::getType()} and is covariant.
 */
interface Collection extends IterableOf
{
    /**
     * Returns the size of the collection.
     */
    public function getSize(): int;

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
}
