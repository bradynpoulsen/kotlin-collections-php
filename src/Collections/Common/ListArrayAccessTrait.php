<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use ArrayAccess;
use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\UnsupportedOperationException;

/**
 * Common implementation of {@see ArrayAccess} methods for {@see ListOf}.
 */
trait ListArrayAccessTrait
{
    /**
     * @param int $index
     * @return bool
     * @see ListOf::offsetExists()
     */
    public function offsetExists($index): bool
    {
        assert($this instanceof ListOf);
        return $this->containsIndex($index);
    }

    /**
     * @param int $index
     * @return mixed
     * @see ListOf::offsetGet()
     */
    public function offsetGet($index)
    {
        assert($this instanceof ListOf);
        return $this->get($index);
    }

    /**
     * @param int $index
     * @param mixed $value
     * @see ListOf::offsetSet()
     * @codeCoverageIgnore
     */
    public function offsetSet($index, $value): void
    {
        assert($this instanceof ListOf);
        if ($this instanceof MutableListOf) {
            $this->set($index, $value);
        } else {
            throw new UnsupportedOperationException();
        }
    }

    /**
     * @param int $index
     * @see ListOf::offsetUnset()
     * @codeCoverageIgnore
     */
    public function offsetUnset($index): void
    {
        assert($this instanceof ListOf);
        if ($this instanceof MutableListOf) {
            $this->removeAt($index);
        } else {
            throw new UnsupportedOperationException();
        }
    }
}
