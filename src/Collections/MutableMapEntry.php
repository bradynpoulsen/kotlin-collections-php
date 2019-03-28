<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

/**
 * Represents a key/value pair held by a {@see MutableMap}.
 * The type of map keys is available through {@see MutableMapEntry::getKeyType()} and is invariant.
 * The type of map values is available through {@see MutableMapEntry::getValueType()} and is invariant.
 */
interface MutableMapEntry extends MapEntry
{
    /**
     * Changes the value associated with the key of this entry.
     *
     * @param mixed $newValue
     * @return mixed the previous value corresponding to the key.
     */
    public function setValue($newValue);
}
