<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use ArrayAccess;
use BradynPoulsen\Kotlin\NoSuchElementException;

/**
 * A generic ordered collection of elements that supports adding and removing elements.
 * The type of elements is available through {@see MutableListOf::getType()} and is invariant.
 *
 * The name "ListOf" and "MutableListOf" was chosen because PHP reserves the keyword "list" for its
 * `list($a, $b) = [1, 2]` language construct. Once this limitation is lifted, it should be migrated
 * to "List" and "MutableList"
 */
interface MutableListOf extends ListOf, MutableCollection
{
    /**
     * Adds the specified $element to the end of this list.
     *
     * @param mixed $element
     * @return bool `true` because the list is always modified as the result of this operation.
     *
     * @see MutableListOf::getType()
     */
    public function add($element): bool;

    /**
     * Adds all of the elements of the specified collection to the end of this list.
     *
     * @param Collection|mixed[] $elements
     * @return bool `true` because the list is always modified as the result of this operation.
     *
     * @see MutableListOf::getType()
     */
    public function addAll(Collection $elements): bool;

    /**
     * Adds the specified $element into this list at the specified $index.
     *
     * @param int $index
     * @param mixed $element
     * @return bool `true` because the list is always modified as the result of this operation.
     *
     * @see MutableListOf::getType()
     */
    public function addAt(int $index, $element): bool;

    /**
     * Adds all of the elements of the specified collection into this list beginning at the specified $index.
     *
     * @param int $index
     * @param Collection|mixed[] $elements
     * @return bool `true` because the list is always modified as the result of this operation.
     *
     * @see MutableListOf::getType()
     */
    public function addAllAt(int $index, Collection $elements): bool;

    /**
     * Replaces the element at the specified $index in this list with the specified $element.
     *
     * @param int $index
     * @param mixed $element
     * @return mixed the element previously at the specified $index.
     * @throws NoSuchElementException if the specified $index is not contained.
     *
     * @see MutableListOf::getType()
     */
    public function set(int $index, $element);

    /**
     * Removes an element at the specified $index from the list.
     *
     * @param int $index
     * @return mixed the element that has been removed
     * @throws NoSuchElementException if the specified $index is not contained.
     *
     * @see MutableListOf::getType()
     */
    public function removeAt(int $index);

    /**
     * {@see ArrayAccess} alias of {@see MutableListOf::set()}
     *
     * @param int $index
     * @param mixed $value
     *
     * @see MutableListOf::getType()
     */
    public function offsetSet($index, $value): void;

    /**
     * {@see ArrayAccess} alias of {@see MutableListOf::removeAt()}
     *
     * @param int $index
     *
     * @see MutableListOf::getType()
     */
    public function offsetUnset($index): void;
}
