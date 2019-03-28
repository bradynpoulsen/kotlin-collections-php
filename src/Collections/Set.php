<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

/**
 * A generic unordered collection of elements that does not support duplicate elements.
 * The type of elements is available through {@see Set::getType()} and is covariant.
 */
interface Set extends Collection
{
}
