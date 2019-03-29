<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use BradynPoulsen\Kotlin\Types\Type;
use IteratorAggregate;
use Traversable;

/**
 * A sequence of elements that can be iterated over.
 * The type of elements is available through {@see IterableOf::getType()} and is covariant.
 *
 * The name "IterableOf" was chosen because PHP reserves the keyword "iterable" for its `iterable`
 * type-hint. Once this limitation is lifted, it should be migrated to "Iterable".
 */
interface IterableOf extends IteratorAggregate
{
    /**
     * Get the type name allowed for elements of this iterable.
     * A {@see TypeError} will be thrown whenever a value is provided that does not qualify as an
     * instance of this type.
     */
    public function getType(): Type;

    /**
     * Returns an iterator over the elements in this iterable.
     */
    public function getIterator(): Traversable;
}
