<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use ArrayAccess;
use BradynPoulsen\Kotlin\Collections\Map;
use BradynPoulsen\Kotlin\Collections\MutableMap;
use BradynPoulsen\Kotlin\UnsupportedOperationException;

/**
 * Implementation of {@see ArrayAccess} methods for {@see Map} and {@see MutableMap}.
 */
trait MapArrayAccessTrait
{
    /**
     * @param mixed $key
     * @return bool
     * @see Map::offsetExists()
     */
    public function offsetExists($key): bool
    {
        assert($this instanceof Map);
        return $this->containsKey($key);
    }

    /**
     * @param mixed $key
     * @return mixed
     * @see Map::offsetGet()
     */
    public function offsetGet($key)
    {
        assert($this instanceof Map);
        return $this->get($key);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @see MutableMap::offsetSet()
     */
    public function offsetSet($key, $value): void
    {
        assert($this instanceof Map);
        if ($this instanceof MutableMap) {
            $this->put($key, $value);
            return;
        }

        throw new UnsupportedOperationException();
    }

    /**
     * @param mixed $key
     * @see MutableMap::offsetUnset()
     */
    public function offsetUnset($key): void
    {
        assert($this instanceof Map);
        if ($this instanceof MutableMap) {
            $this->remove($key);
            return;
        }

        throw new UnsupportedOperationException();
    }
}
