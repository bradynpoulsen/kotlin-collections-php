<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use OutOfBoundsException;

/**
 * {@see MutableListOf} implementation backed by a PHP array.
 * @internal
 */
class MutableArrayList extends ArrayList implements MutableListOf
{
    use MutableListTrait;

    /**
     * @param int $index
     * @param mixed $element
     * @return bool
     * @see MutableListOf::addAt()
     */
    public function addAt(int $index, $element): bool
    {
        TypeAssurance::ensureContainedArgumentValue($this->getType(), 2, $element);
        if ($this->count() === $index) {
            return $this->add($element);
        }

        if (!$this->containsIndex($index)) {
            throw new OutOfBoundsException();
        }

        array_splice($this->container, $index, 0, [$element]);

        return true;
    }

    /**
     * @param int $index
     * @param Collection $elements
     * @return bool
     * @see MutableListOf::addAllAt()
     */
    public function addAllAt(int $index, Collection $elements): bool
    {
        TypeAssurance::ensureContainedArgumentType($this->getType(), 2, $elements->getType(), get_class($elements));
        if ($this->count() === $index) {
            return $this->addAll($elements);
        }

        if (!$this->containsIndex($index)) {
            throw new OutOfBoundsException();
        }

        array_splice($this->container, $index, 0, $elements->toArray());

        return true;
    }

    /**
     * @param int $index
     * @param mixed $element
     * @return mixed
     * @see MutableListOf::set()
     */
    public function set(int $index, $element)
    {
        TypeAssurance::ensureContainedArgumentValue($this->getType(), 2, $element);
        if (!$this->containsIndex($index)) {
            throw new NoSuchElementException();
        }

        $replaced = array_splice($this->container, $index, 1, [$element])[0];

        assert(
            $this->getType()->containsValue($replaced),
            '$replaced must be a covariant value of ' . $this->getType()->getName()
        );
        return $replaced;
    }

    /**
     * @param int $index
     * @return mixed
     * @see MutableListOf::removeAt()
     */
    public function removeAt(int $index)
    {
        if (!$this->containsIndex($index)) {
            throw new NoSuchElementException();
        }

        $removed = array_splice($this->container, $index, 1)[0];

        assert(
            $this->getType()->containsValue($removed),
            '$removed must be a covariant value of ' . $this->getType()->getName()
        );
        return $removed;
    }
}
