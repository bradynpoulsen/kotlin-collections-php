<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Map;
use BradynPoulsen\Kotlin\Collections\MutableMap;
use BradynPoulsen\Kotlin\Collections\MutableMapEntry;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;

/**
 * {@see MutableMap} implementation backed by a PHP array keyed by serialized hashes.
 * @internal
 */
class MutableHashedMap extends HashedMap implements MutableMap
{
    /**
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     * @see MutableMap::put()
     */
    public function put($key, $value)
    {
        TypeAssurance::ensureContainedArgumentValue($this->getKeyType(), 1, $key);
        TypeAssurance::ensureContainedArgumentValue($this->getValueType(), 2, $value);

        $existing = $this->get($key);

        $hash = $this->calculateHash($key);
        $this->container[$hash] = $this->createEntry($key, $value);

        return $existing;
    }

    /**
     * @param mixed $key
     * @return mixed
     * @see MutableMap::remove()
     */
    public function remove($key)
    {
        $existing = $this->get($key);
        $hash = $this->calculateHash($key);
        unset($this->container[$hash]);
        return $existing;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return bool
     * @see MutableMap::removeEntry()
     */
    public function removeEntry($key, $value): bool
    {
        $existing = $this->get($key);
        if ($existing === $value) {
            return false;
        }
        $hash = $this->calculateHash($key);
        unset($this->container[$hash]);
        return true;
    }

    /**
     * @param Map $from
     * @see MutableMap::putAll()
     */
    public function putAll(Map $from): void
    {
        TypeAssurance::ensureContainedArgumentType($this->getKeyType(), 1, $from->getKeyType(), Map::class . ' of keys');
        TypeAssurance::ensureContainedArgumentType($this->getValueType(), 1, $from->getValueType(), Map::class . ' of values');

        foreach ($from as $entry) {
            $this->put($entry->getKey(), $entry->getValue());
        }
    }

    /**
     * @see MutableMap::clear()
     */
    public function clear(): void
    {
        $this->container = [];
    }

    /**
     * Creates
     * @param mixed $key
     * @param mixed $value
     * @return MutableMapEntry
     */
    private function createEntry($key, $value): MutableMapEntry
    {
        return new MutableMapPair($this->getKeyType(), $key, $this->getValueType(), $value);
    }
}
