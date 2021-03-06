<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use ArrayAccess;
use BradynPoulsen\Kotlin\Types\Type;
use Countable;
use Traversable;

/**
 * A collection that holds pairs of objects (keys and values) and supports efficiently retrieving
 * the value corresponding to each key. Map keys are unique; the map holds only one value for each
 * key.
 * The type of map keys is available through {@see Map::getKeyType()}.
 * The type of map values is available through {@see Map::getValueType()}.
 */
interface Map extends IterableOf, ArrayAccess, Countable
{
    /**
     * A type representing {@see MapEntry}.
     * When iterating over a {@see Map} as a {@see Traversable}, {@see MapEntry} key/value pairs will be provided.
     */
    public function getType(): Type;

    /**
     * Get the type name allowed for keys of this map.
     * A {@see TypeError} will be thrown whenever a value is provided that does not qualify as an
     * instance of this type.
     */
    public function getKeyType(): Type;

    /**
     * Get the type name allowed for values of this map.
     * A {@see TypeError} will be thrown whenever a value is provided that does not qualify as an
     * instance of this type.
     */
    public function getValueType(): Type;

    /**
     * Returns the number of key/value pairs in the map.
     */
    public function count(): int;

    /**
     * Returns `true` if the map is empty (contains no elements), `false` otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Check if the map contains a key.
     *
     * @param mixed $key
     * @return bool `true` if the map contains the specified $key, `false` otherwise.
     *
     * @see Map::getKeyType()
     */
    public function containsKey($key): bool;

    /**
     * Check if the map contains a value.
     *
     * @param mixed $value
     * @return bool `true` if the map contains the specified $value, `false` otherwise.
     *
     * @see Map::getValueType()
     */
    public function containsValue($value): bool;

    /**
     * Get a value by its $key.
     *
     * @param mixed $key
     * @return mixed the value corresponding the given $key, or `null` if no such key is present in the map.
     *
     * @see Map::getKeyType()
     * @see Map::getValueType()
     */
    public function get($key);

    /**
     * Returns a read-only {@see Set} of all keys in this map.
     *
     * @return Set|mixed[]
     *
     * @see Map::getKeyType()
     */
    public function getKeys(): Set;

    /**
     * Returns a read-only {@see Collection} of all values in this map. Note that this collection
     * may contain duplicate values.
     *
     * @return Collection|mixed[]
     *
     * @see Map::getValueType()
     */
    public function getValues(): Collection;

    /**
     * Returns an iterator over the key/value pairs in this map.
     *
     * @return Traversable|MapEntry[]
     */
    public function getIterator(): Traversable;

    /**
     * Collect the key/value pairs of this map into a read-only {@see Map}.
     *
     * @return Map
     */
    public function toMap(): Map;

    /**
     * Collect the key/value pairs of this map into a {@see MutableMap}.
     *
     * @return MutableMap
     */
    public function toMutableMap(): MutableMap;

    /**
     * {@see ArrayAccess} equivalent of {@see Map::containsKey()}
     *
     * @param mixed $key
     * @return bool
     *
     * @see Map::getKeyType()
     */
    public function offsetExists($key): bool;

    /**
     * {@see ArrayAccess} equivalent of {@see Map::get()}
     *
     * @param mixed $key
     * @return mixed
     *
     * @see Map::getKeyType()
     */
    public function offsetGet($key);

    /**
     * @param $key
     * @param $value
     * @deprecated Unsupported mutation operation of this read-only map.
     */
    public function offsetSet($key, $value): void;

    /**
     * @param $key
     * @deprecated Unsupported mutation operation of this read-only map.
     */
    public function offsetUnset($key): void;
}
