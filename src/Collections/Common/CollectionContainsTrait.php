<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use BradynPoulsen\Kotlin\Collections\Collection;

/**
 * Common implementation of contains* methods for {@see Collection}.
 */
trait CollectionContainsTrait
{
    /**
     * @param mixed $element
     * @return bool
     * @see Collection::contains()
     */
    public function contains($element): bool
    {
        assert($this instanceof Collection);
        foreach ($this as $item) {
            if ($element === $item) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Collection $elements
     * @return bool
     * @see Collection::containsAll()
     */
    public function containsAll(Collection $elements): bool
    {
        assert($this instanceof Collection);
        if ($this === $elements) {
            return true;
        }

        foreach ($elements as $item) {
            if (!$this->contains($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Collection $elements
     * @return bool
     * @see Collection::containsAny()
     */
    public function containsAny(Collection $elements): bool
    {
        assert($this instanceof Collection);
        if ($this === $elements) {
            return true;
        }

        foreach ($elements as $item) {
            if ($this->contains($item)) {
                return true;
            }
        }
        return false;
    }
}
