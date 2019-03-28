<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

/**
 * A generic collection of elements that supports adding and removing elements.
 * The type of elements is available through {@see MutableCollection::getType()} and is invariant.
 */
interface MutableCollection extends Collection
{
    /**
     * Adds the specified element to the collection.
     *
     * @param mixed $element
     * @return bool `true` if the element has been added, `false` if the collection does not
     *     support duplicates and the element is already contained in the collection.
     *
     * @see MutableCollection::getType()
     */
    public function add($element): bool;

    /**
     * Removes a single instance of the specified element from this collection, if it is present.
     *
     * @param mixed $element
     * @return bool `true` if the element has been successfully removed; `false` if it was not
     *     present in the collection.
     *
     * @see MutableCollection::getType()
     */
    public function remove($element): bool;

    /**
     * Adds all of the elements of the specified collection to this collection.
     *
     * @param Collection|mixed[] $elements
     * @return bool `true` if any of the specified elements were added to the collection, `false`
     *     if the collection was not modified.
     *
     * @see MutableCollection::getType()
     */
    public function addAll(Collection $elements): bool;

    /**
     * Removes all of this collection's elements that are also contained in the specified collection.
     *
     * @param Collection|mixed[] $elements
     * @return bool `true` if any of the specified elements were removed from the collection,
     *     `false` if the collection was not modified.
     *
     * @see MutableCollection::getType()
     */
    public function removeAll(Collection $elements): bool;

    /**
     * Retains only the elements in this collection that are contained in the specified collection.
     *
     * @param Collection|mixed[] $elements
     * @return bool `true` if any element was removed from the collection, `false` if the collection
     *     was not modified.
     *
     * @see MutableCollection::getType()
     */
    public function retainAll(Collection $elements): bool;

    /**
     * Removes all elements from this collection.
     */
    public function clear(): void;
}
