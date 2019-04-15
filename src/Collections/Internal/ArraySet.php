<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Collections\Set;

/**
 * {@see Set} implementation backed by a PHP array.
 * @internal
 */
class ArraySet extends AbstractArrayCollection implements Set
{
}
