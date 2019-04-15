<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\MutableSet;

/**
 * {@see MutableSet} implementation backed by a PHP array.
 * @internal
 */
class MutableArraySet extends ArraySet implements MutableSet
{
    use MutableSetTrait;
}
