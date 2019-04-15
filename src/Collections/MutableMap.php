<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use Traversable;

/**
 * A modifiable {@see Map}.
 * The type of map keys is available through {@see MutableMap::getKeyType()} and is invariant.
 * The type of map values is available through {@see MutableMap::getValueType()} and is invariant.
 */
interface MutableMap extends Map
{
    /**
     * Associates the specified $value with the specified $key in this map.
     *
     * @param mixed $key
     * @param mixed $value
     * @return mixed the previous value associated with the key, or `null` if the key was not
     *     present in the map.
     *
     * @see MutableMap::getKeyType()
     * @see MutableMap::getValueType()
     */
    public function put($key, $value);

    /**
     * Removes the specified $key and its corresponding $value from this map.
     *
     * @param mixed $key
     * @return mixed the previous value associated with the key, or `null` if the key was not
     *     present in the map.
     *
     * @see MutableMap::getKeyType()
     */
    public function remove($key);

    /**
     * Removes the entry for the specified $key only if it is mapped with the specified $value.
     *
     * @param mixed $key
     * @param mixed $value
     * @return bool `true` if the entry was removed, `false` otherwise.
     *
     * @see MutableMap::getKeyType()
     * @see MutableMap::getValueType()
     */
    public function removeEntry($key, $value): bool;

    /**
     * Updates this map with key/value pairs from the specified {@see Map} $from.
     *
     * @param Map|MapEntry[] $from
     *
     * @see MutableMap::getKeyType()
     * @see MutableMap::getValueType()
     */
    public function putAll(Map $from): void;

    /**
     * Removes all elements from this map.
     */
    public function clear(): void;

    /**
     * Returns a {@see MutableSet} of all keys in this map.
     *
     * @return MutableSet|mixed[]
     *
     * @see MutableMap::getKeyType()
     */
    public function getKeys(): Set;

    /**
     * Returns a {@see MutableCollection} of all values in this map. Note that this collection
     * may contain duplicate values.
     *
     * @return MutableCollection|mixed[]
     *
     * @see MutableMap::getValueType()
     */
    public function getValues(): Collection;

    /**
     * Returns an iterator over the key/value pairs in this map.
     *
     * @return Traversable|MutableMapEntry[]
     */
    public function getIterator(): Traversable;

    /**
     * {@see ArrayAccess} equivalent of {@see MutableMap::put()}
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @see MutableMap::getKeyType()
     * @see MutableMap::getValueType()
     */
    public function offsetSet($key, $value): void;

    /**
     * {@see ArrayAccess} equivalent of {@see MutableMap::remove()}
     *
     * @param mixed $key
     *
     * @see MutableMap::getKeyType()
     */
    public function offsetUnset($key): void;
}
