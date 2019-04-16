<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;

/**
 * @internal
 */
trait MutableSetTrait
{
    use MutableArrayCollectionTrait;

    /**
     * @param mixed $element
     * @return bool
     * @see MutableCollection::add()
     */
    public function add($element): bool
    {
        assert($this instanceof AbstractArrayCollection);
        TypeAssurance::ensureContainedValue($this->getType(), 1, $element);
        if ($this->contains($element)) {
            return false;
        }
        array_push($this->container, $element);
        return true;
    }

    /**
     * @param Collection $elements
     * @return bool
     * @see MutableCollection::addAll()
     */
    public function addAll(Collection $elements): bool
    {
        assert($this instanceof AbstractArrayCollection);
        TypeAssurance::ensureContainedType($this->getType(), 1, $elements->getType(), get_class($elements));
        $originalSize = $this->count();
        foreach ($elements as $element) {
            $this->add($element);
        }
        return $this->count() !== $originalSize;
    }
}
