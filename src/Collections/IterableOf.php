<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use IteratorAggregate;
use Traversable;

/**
 * A sequence of elements that can be iterated over.
 *
 * The name "IterableOf" was chosen because PHP reserves the keyword "iterable" for its `iterable`
 * type-hint. Once this limitation is lifted, it should be migrated to "Iterable".
 */
interface IterableOf extends IteratorAggregate
{
    /**
     * Returns an iterator over the elements in this iterable.
     */
    public function getIterator(): Traversable;

    /**
     * Collect all of the elements of this iterable into a read-only list.
     */
    public function toList(): ListOf;

    /**
     * Collect all of the elements of this iterable into a mutable list.
     */
    public function toMutableList(): MutableListOf;

    /**
     * Collect all of the elements of this iterable into a read-only set.
     */
    public function toSet(): Set;

    /**
     * Collect all of the elements of this iterable into a mutable set.
     */
    public function toMutableSet(): MutableSet;
}
