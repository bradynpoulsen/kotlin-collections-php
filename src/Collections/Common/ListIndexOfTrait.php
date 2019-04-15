<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use BradynPoulsen\Kotlin\Collections\ListOf;

/**
 * Common implementation of indexOf* methods for {@see ListOf}.
 */
trait ListIndexOfTrait
{
    /**
     * @param int $index
     * @return bool
     * @see ListOf::containsIndex()
     */
    public function containsIndex(int $index): bool
    {
        assert($this instanceof ListOf);
        return 0 <= $index && $index < $this->count();
    }

    /**
     * @param mixed $element
     * @return int
     * @see ListOf::indexOfFirst()
     */
    public function indexOfFirst($element): ?int
    {
        assert($this instanceof ListOf);
        for ($index = 0; $index < $this->count(); $index++) {
            $item = $this->get($index);
            if ($element === $item) {
                return $index;
            }
        }
        return null;
    }

    /**
     * @param mixed $element
     * @return int
     * @see ListOf::indexOfLast()
     */
    public function indexOfLast($element): ?int
    {
        assert($this instanceof ListOf);
        for ($index = $this->count() - 1; $index >= 0; $index--) {
            $item = $this->get($index);
            if ($element === $item) {
                return $index;
            }
        }
        return null;
    }
}
