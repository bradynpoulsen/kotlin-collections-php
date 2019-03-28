<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

/**
 * Represents a key/value pair held by a {@see Map}.
 * The type of map keys is available through {@see MapEntry::getKeyType()} and is invariant.
 * The type of map values is available through {@see MapEntry::getValueType()} and is covariant.
 */
interface MapEntry
{
    /**
     * Get the type name allowed for keys of this map.
     * A {@see TypeError} will be thrown whenever a value is provided that does not qualify as an
     * instance of this type.
     */
    public function getKeyType(): string;

    /**
     * Get the type name allowed for values of this map.
     * A {@see TypeError} will be thrown whenever a value is provided that does not qualify as an
     * instance of this type.
     */
    public function getValueType(): string;

    /**
     * Returns the key of this key/value pair.
     *
     * @return mixed
     *
     * @see MapEntry::getKeyType()
     */
    public function getKey();

    /**
     * Returns the value of this key/value pair.
     *
     * @return mixed
     *
     * @see MapEntry::getValueType()
     */
    public function getValue();
}
