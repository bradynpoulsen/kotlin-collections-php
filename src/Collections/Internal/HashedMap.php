<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use ArrayIterator;
use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\Common\MapArrayAccessTrait;
use BradynPoulsen\Kotlin\Collections\IterableOf;
use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\Map;
use BradynPoulsen\Kotlin\Collections\MapEntry;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\Collections\MutableMap;
use BradynPoulsen\Kotlin\Collections\MutableMapEntry;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Collections\Set;
use BradynPoulsen\Kotlin\Sequences\Internal\IterableOfSequence;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use Traversable;
use function BradynPoulsen\Kotlin\Collections\listOf;
use function BradynPoulsen\Kotlin\Collections\mutableListOf;
use function BradynPoulsen\Kotlin\Collections\mutableSetOf;
use function BradynPoulsen\Kotlin\Collections\setOf;

/**
 * {@see Map} implementation backed by a PHP array keyed by serialized hashes.
 * @internal
 */
class HashedMap implements Map
{
    use MapArrayAccessTrait;

    /**
     * Container of hashed keys to {@see MapEntry} values.
     * @var MapEntry[]
     */
    protected $container = [];

    /**
     * @var Type
     */
    private $keyType;

    /**
     * @var Type
     */
    private $valueType;

    final public function __construct(Type $keyType, Type $valueType)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s<%s, %s>{%s}',
            static::class,
            $this->getKeyType(),
            $this->getValueType(),
            implode(', ', $this->container)
        );
    }

    /**
     * @return Type
     * @see Map::getKeyType()
     */
    public function getKeyType(): Type
    {
        return $this->keyType;
    }

    /**
     * @return Type
     * @see Map::getValueType()
     */
    public function getValueType(): Type
    {
        return $this->valueType;
    }

    /**
     * @return int
     * @see Map::count()
     */
    public function count(): int
    {
        return count($this->container);
    }

    /**
     * @return bool
     * @see Map::isEmpty()
     */
    public function isEmpty(): bool
    {
        return empty($this->container);
    }

    /**
     * @param mixed $key
     * @return bool
     * @see Map::containsKey()
     */
    public function containsKey($key): bool
    {
        return array_key_exists($this->calculateHash($key), $this->container);
    }

    /**
     * @param mixed $value
     * @return bool
     * @see Map::containsValue()
     */
    public function containsValue($value): bool
    {
        foreach ($this->container as $entry) {
            if ($entry->getValue() === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed $key
     * @return mixed
     * @see Map::get()
     */
    public function get($key)
    {
        $hash = $this->calculateHash($key);
        $entry = $this->container[$hash] ?? null;
        if (is_null($entry)) {
            return null;
        }

        return $entry->getValue();
    }

    /**
     * @return Set
     * @see Map::getKeys()
     */
    public function getKeys(): Set
    {
        $keys = array_map(
            function (MapEntry $entry) {
                return $entry->getKey();
            },
            $this->container
        );
        return setOf($this->getKeyType(), $keys);
    }

    /**
     * @return Collection
     * @see Map::getValues()
     */
    public function getValues(): Collection
    {
        $values = array_map(
            function (MapEntry $entry) {
                return $entry->getValue();
            },
            $this->container
        );
        return listOf($this->getValueType(), $values);
    }

    /**
     * @return Traversable|MapEntry[]
     * @see IterableOf::getIterator()
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator(array_values($this->container));
    }

    /**
     * @return Type
     * @see IterableOf::getType()
     */
    public function getType(): Type
    {
        return Types::instance(MapEntry::class);
    }

    /**
     * @return Sequence
     * @see IterableOf::asSequence()
     */
    public function asSequence(): Sequence
    {
        return new IterableOfSequence($this);
    }


    /**
     * @return ListOf
     * @see IterableOf::toList()
     */
    public function toList(): ListOf
    {
        return listOf(Types::instance(MapEntry::class), $this->container);
    }

    /**
     * @return MutableListOf
     * @see IterableOf::toMutableList()
     */
    public function toMutableList(): MutableListOf
    {
        return mutableListOf(Types::instance(MapEntry::class), $this->container);
    }

    /**
     * @return Set
     * @see IterableOf::toSet()
     */
    public function toSet(): Set
    {
        return setOf(Types::instance(MapEntry::class), $this->container);
    }

    /**
     * @return MutableSet
     * @see IterableOf::toMutableSet()
     */
    public function toMutableSet(): MutableSet
    {
        return mutableSetOf(Types::instance(MapEntry::class), $this->container);
    }

    /**
     * @return Map
     * @see Map::toMap()
     */
    public function toMap(): Map
    {
        if (!($this instanceof MutableMap)) {
            return $this;
        }

        $map = new HashedMap($this->getKeyType(), $this->getValueType());
        $map->container = array_map(function (MapEntry $entry): MapEntry {
            if ($entry instanceof MutableMapEntry) {
                return new MapPair(
                    $entry->getKeyType(),
                    $entry->getKey(),
                    $entry->getValueType(),
                    $entry->getValue()
                );
            }

            return $entry;
        }, $this->container);
        return $map;
    }

    /**
     * @return MutableMap
     * @see Map::toMutableMap()
     */
    public function toMutableMap(): MutableMap
    {
        if ($this instanceof MutableMap) {
            return $this;
        }

        $map = new MutableHashedMap($this->getKeyType(), $this->getValueType());
        $map->container = array_map(function (MapEntry $entry): MutableMapEntry {
            if ($entry instanceof MutableMapEntry) {
                return $entry;
            }

            return new MutableMapPair(
                $entry->getKeyType(),
                $entry->getKey(),
                $entry->getValueType(),
                $entry->getValue()
            );
        }, $this->container);
        return $map;
    }

    /**
     * Calculate the hash string that uniquely identifies the specified $key.
     *
     * @param mixed $key
     * @return string
     */
    protected function calculateHash($key): string
    {
        return ElementHashCalculator::calculate($key);
    }
}
