<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;


use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;

trait MutableListTrait
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
        array_splice($this->container, $this->count(), 0, $elements->toArray());
        return true;
    }
}
