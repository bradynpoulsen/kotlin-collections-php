<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\MutableCollection;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;

/**
 * {@see MutableSet} implementation backed by a PHP array unique by serial hashes.
 * @see ElementHashCalculator::calculate()
 * @internal
 */
class MutableHashedSet extends HashedSet implements MutableSet
{
    use MutableSetTrait;

    /**
     * @param mixed $element
     * @return bool
     * @see MutableCollection::add()
     */
    public function add($element): bool
    {
        TypeAssurance::ensureContainedArgumentValue($this->getType(), 1, $element);

        $hash = $this->calculateHash($element);
        if ($this->containsHash($hash)) {
            return false;
        }

        $this->container[$hash] = $element;
        return true;
    }

    /**
     * @param mixed $element
     * @return bool
     * @see MutableCollection::remove()
     */
    public function remove($element): bool
    {
        $hash = $this->calculateHash($element);
        if (!$this->containsHash($hash)) {
            return false;
        }

        unset($this->container[$hash]);
        return true;
    }

    /**
     * @param Collection $elements
     * @return bool
     * @see MutableCollection::addAll()
     */
    public function addAll(Collection $elements): bool
    {
        TypeAssurance::ensureContainedArgumentType($this->getType(), 1, $elements->getType(), get_class($elements));

        $originalSize = $this->count();
        foreach ($elements as $element) {
            $this->add($element);
        }
        return $this->count() !== $originalSize;
    }
}
