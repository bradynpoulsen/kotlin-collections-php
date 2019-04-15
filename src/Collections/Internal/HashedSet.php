<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\Set;

/**
 * {@see Set} implementation backed by a PHP array unique by serial hashes.
 * @see ElementHashCalculator::calculate()
 * @internal
 */
class HashedSet extends AbstractArrayCollection implements Set
{
    /**
     * @param mixed $element
     * @return bool
     * @see Collection::contains()
     */
    public function contains($element): bool
    {
        return $this->containsHash($this->calculateHash($element));
    }

    /**
     * Calculate the hash string that uniquely identifies the specified $element.
     *
     * @param mixed $element
     * @return string
     */
    protected function calculateHash($element): string
    {
        return ElementHashCalculator::calculate($element);
    }

    /**
     * @param string $hash
     * @return bool `true` if the specified $hash exists in this set.
     */
    protected function containsHash(string $hash): bool
    {
        return array_key_exists($hash, $this->container);
    }
}
