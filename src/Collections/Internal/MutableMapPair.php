<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\MutableMapEntry;

/**
 * {@see MutableMapEntry} implementation.
 * @internal
 */
class MutableMapPair extends MapPair implements MutableMapEntry
{
    /**
     * @param mixed $newValue
     * @return mixed
     * @see MutableMapEntry::setValue()
     */
    public function setValue($newValue)
    {
        return parent::setValue($newValue);
    }
}
