<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use ArrayAccess;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\UnsupportedOperationException;
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
     * Get the index of the first occurrence of the specified $element.
     *
     * @param $element
     * @return int the index of the first occurrence, or `-1` if not contained.
     *
     * @see ListOf::getType()
     */
    public function indexOfFirst($element): int;

    /**
     * Get the index of the last occurrence of the specified $element.
     *
     * @param $element
     * @return int the index of the last occurrence, or `-1` if not contained.
     *
     * @see ListOf::getType()
     */
    public function indexOfLast($element): int;

    /**
     * Returns an iterator over the elements in this list in proper sequence.
     */
    public function getIterator(): Traversable;

    /**
     * {@see ArrayAccess} equivalent of {@example $list->indexOfFirst($element) !== -1}
     *
     * @param mixed $index
     * @return bool
     */
    public function offsetExists($index): bool;

    /**
     * {@see ArrayAccess} equivalent of {@see ListOf::get()}
     *
     * @param mixed $index
     * @return mixed
     *
     * @see ListOf::getType()
     */
    public function offsetGet($index);

    /**
     * @deprecated Unsupported mutation operation of this read-only list.
     * @throws UnsupportedOperationException always
     */
    public function offsetSet($offset, $value): void;

    /**
     * @deprecated Unsupported mutation operation of this read-only list.
     * @throws UnsupportedOperationException always
     */
    public function offsetUnset($offset): void;
}
