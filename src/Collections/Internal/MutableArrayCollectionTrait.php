<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\IterableOf;
use BradynPoulsen\Kotlin\Collections\MutableCollection;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see MutableCollection} implementation backed by a PHP array.
 * @see AbstractArrayCollection
 * @internal
 */
trait MutableArrayCollectionTrait
{
    /**
     * @see IterableOf::getType()
     */
    abstract public function getType(): Type;

    /**
     * @param mixed $element
     * @return bool
     * @see MutableCollection::remove()
     */
    public function remove($element): bool
    {
        assert($this instanceof AbstractArrayCollection);
        $originalSize = $this->count();
        $this->container = array_filter($this->container, function ($item) use ($element): bool {
            return $element !== $item;
        });
        return $this->count() !== $originalSize;
    }

    /**
     * @param Collection $elements
     * @return bool
     * @see MutableCollection::removeAll()
     */
    public function removeAll(Collection $elements): bool
    {
        assert($this instanceof AbstractArrayCollection);
        $originalSize = $this->count();
        $this->container = array_filter($this->container, function ($item) use ($elements): bool {
            return !$elements->contains($item);
        });
        return $this->count() !== $originalSize;
    }

    /**
     * @param Collection $elements
     * @return bool
     * @see MutableCollection::retainAll()
     */
    public function retainAll(Collection $elements): bool
    {
        assert($this instanceof AbstractArrayCollection);
        $originalSize = $this->count();
        $this->container = array_filter($this->container, function ($item) use ($elements): bool {
            return $elements->contains($item);
        });
        return $this->count() !== $originalSize;
    }

    /**
     * @see MutableCollection::clear()
     */
    public function clear(): void
    {
        assert($this instanceof AbstractArrayCollection);
        $this->container = [];
    }
}
