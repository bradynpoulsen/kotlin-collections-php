<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use ArrayAccess;
use BradynPoulsen\Kotlin\NoSuchElementException;
use Traversable;

/**
 * A generic ordered collection of elements.
 * The type of elements is available through {@see ListOf::getType()} and is covariant.
 *
 * The name "ListOf" and "MutableListOf" was chosen because PHP reserves the keyword "list" for its
 * `list($a, $b) = [1, 2]` language construct. Once this limitation is lifted, it should be migrated
 * to "List" and "MutableList"
 */
interface ListOf extends Collection, ArrayAccess
{
    /**
     * Returns the element at the specified index in this list.
     *
     * @param int $index
     * @return mixed
     * @throws NoSuchElementException if the specified $index does not exist
     *
     * @see ListOf::getType()
     */
    public function get(int $index);

    /**
     * Checks if the specified $index is contained in this collection.
     *
     * @param int $index
     * @return bool
     */
    public function containsIndex(int $index): bool;

    /**
     * Get the index of the first occurrence of the specified $element.
     *
     * @param $element
     * @return int|null the index of the first occurrence, or `null` if not contained.
     *
     * @see ListOf::getType()
     */
    public function indexOfFirst($element): ?int;

    /**
     * Get the index of the last occurrence of the specified $element.
     *
     * @param $element
     * @return int|null the index of the last occurrence, or `null` if not contained.
     *
     * @see ListOf::getType()
     */
    public function indexOfLast($element): ?int;

    /**
     * Returns an iterator over the elements in this list in proper sequence.
     */
    public function getIterator(): Traversable;

    /**
     * {@see ArrayAccess} equivalent of {@see ListOf::containsIndex()}
     *
     * @param int $index
     * @return bool
     */
    public function offsetExists($index): bool;

    /**
     * {@see ArrayAccess} equivalent of {@see ListOf::get()}
     *
     * @param int $index
     * @return mixed
     *
     * @see ListOf::getType()
     */
    public function offsetGet($index);

    /**
     * @param $offset
     * @param $value
     * @deprecated Unsupported mutation operation of this read-only list.
     */
    public function offsetSet($offset, $value): void;

    /**
     * @param $offset
     * @deprecated Unsupported mutation operation of this read-only list.
     */
    public function offsetUnset($offset): void;
}
