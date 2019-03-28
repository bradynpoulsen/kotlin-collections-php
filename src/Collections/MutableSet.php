<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

/**
 * A generic unordered collection of elements that does not support duplicate elements, and
 * supports adding and removing elements.
 * The type of elements is available through {@see MutableSet::getType()} and is invariant.
 */
interface MutableSet extends Set, MutableCollection
{
}
