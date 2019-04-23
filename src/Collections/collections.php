<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections;

use BradynPoulsen\Kotlin\Collections\Internal\MutableArrayList;
use BradynPoulsen\Kotlin\Collections\Internal\MutableArraySet;
use BradynPoulsen\Kotlin\Collections\Internal\MutableHashedMap;
use BradynPoulsen\Kotlin\Collections\Internal\MutableHashedSet;
use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;

/**
 * Provided builder method of a read-only {@see ListOf} implementation.
 * @param Type $type
 * @param mixed[] $elements
 * @return ListOf
 */
function listOf(Type $type, array $elements = []): ListOf
{
    return mutableListOf($type, $elements)->toList();
}

/**
 * Provided builder method of a {@see MutableListOf} implementation.
 * @param Type $type
 * @param mixed[] $elements
 * @return MutableListOf
 */
function mutableListOf(Type $type, array $elements = []): MutableListOf
{
    $list = new MutableArrayList($type);
    foreach ($elements as $element) {
        $list->add($element);
    }
    return $list;
}

/**
 * Provided builder method of unchecked values inside a read-only {@see ListOf} implementation.
 * @param mixed[] $elements
 * @return ListOf
 */
function unsafeListOf(array $elements = []): ListOf
{
    return mutableListOf(Types::mixed(), $elements)->toList();
}

/**
 * Provided builder method of unchecked values inside a {@see MutableListOf} implementation.
 * @param mixed[] $elements
 * @return MutableListOf
 */
function unsafeMutableListOf(array $elements = []): MutableListOf
{
    return mutableListOf(Types::mixed(), $elements);
}

/**
 * Provided builder method of a read-only {@see Set} implementation.
 * @param Type $type
 * @param mixed[] $elements
 * @return Set
 */
function setOf(Type $type, array $elements = []): Set
{
    return mutableSetOf($type, $elements)->toSet();
}

/**
 * Provided builder method of a {@see MutableSet} implementation.
 * @param Type $type
 * @param mixed[] $elements
 * @return MutableSet
 */
function mutableSetOf(Type $type, array $elements = []): MutableSet
{
    $set = $type->acceptsDynamicArray() ? new MutableArraySet($type) : new MutableHashedSet($type);
    assert($set instanceof MutableSet);
    foreach ($elements as $element) {
        $set->add($element);
    }
    return $set;
}

/**
 * Provided builder method of unchecked values inside a read-only {@see Set} implementation.
 * @param mixed[] $elements
 * @return Set
 */
function unsafeSetOf(array $elements = []): Set
{
    return mutableSetOf(Types::mixed(), $elements)->toSet();
}

/**
 * Provided builder method of unchecked values inside a {@see MutableSet} implementation.
 * @param mixed[] $elements
 * @return MutableSet
 */
function unsafeMutableSetOf(array $elements = []): MutableSet
{
    return mutableSetOf(Types::mixed(), $elements);
}

/**
 * Provided builder method of a read-only {@see Map} implementation.
 * @param Type $keyType
 * @param Type $valueType
 * @param Pair[] $pairs
 * @return Map
 */
function mapOf(Type $keyType, Type $valueType, array $pairs = []): Map
{
    return mutableMapOf($keyType, $valueType, $pairs)->toMap();
}

/**
 * Provided builder method of a {@see MutableMap} implementation.
 * @param Type $keyType
 * @param Type $valueType
 * @param Pair[] $pairs
 * @return MutableMap
 */
function mutableMapOf(Type $keyType, Type $valueType, array $pairs = []): MutableMap
{
    $pairType = Types::instance(Pair::class);
    $map = new MutableHashedMap($keyType, $valueType);
    foreach ($pairs as $index => $pair) {
        TypeAssurance::ensureContainedArgumentValue($pairType, 3, $pair, sprintf('Index %d for array', $index));
        $map->put($pair->getFirst(), $pair->getSecond());
    }
    return $map;
}

/**
 * Provided builder method of unchecked values inside a read-only {@see Map} implementation.
 * @param Pair[] $pairs
 * @return Map
 */
function unsafeMapOf(array $pairs = []): Map
{
    return mutableMapOf(Types::mixed(), Types::mixed(), $pairs)->toMap();
}

/**
 * Provided builder method of unchecked values inside a {@see MutableMap} implementation.
 * @param Pair[] $pairs
 * @return MutableMap
 */
function unsafeMutableMapOf(array $pairs = []): MutableMap
{
    return mutableMapOf(Types::mixed(), Types::mixed(), $pairs);
}
