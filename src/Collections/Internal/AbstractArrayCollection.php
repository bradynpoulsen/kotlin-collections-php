<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use ArrayIterator;
use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\Common\CollectionContainsTrait;
use BradynPoulsen\Kotlin\Collections\Common\CollectionOperatorsTrait;
use BradynPoulsen\Kotlin\Collections\IterableOf;
use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Collections\Set;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IterableOfSequence;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Internal\StringSerializer;
use BradynPoulsen\Kotlin\Types\Type;
use Traversable;
use function BradynPoulsen\Kotlin\Collections\mutableSetOf;
use function BradynPoulsen\Kotlin\Collections\setOf;

/**
 * {@see Collection} implementation backed by a PHP array.
 * @internal
 */
abstract class AbstractArrayCollection implements Collection
{
    use CollectionContainsTrait;
    use CollectionOperatorsTrait;

    /**
     * @var mixed[]
     */
    protected $container = [];

    /**
     * @var Type
     */
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @see IterableOf::getType()
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @see Collection::count()
     */
    public function count(): int
    {
        return count($this->container);
    }

    /**
     * @see Collection::isEmpty()
     */
    public function isEmpty(): bool
    {
        return empty($this->container);
    }

    /**
     * @see IterableOf::getIterator()
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->container);
    }

    /**
     * Build a string representation of this collection.
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%s<%s>[%s]',
            static::class,
            $this->getType(),
            implode(', ', array_map([StringSerializer::class, 'prepareValue'], $this->container))
        );
    }

    /**
     * @see IterableOf::asSequence()
     */
    public function asSequence(): Sequence
    {
        return new IterableOfSequence($this);
    }


    /**
     * Collect all of the elements of this collection into an array.
     * @return mixed[]
     * @see Collection::toArray()
     */
    public function toArray(): array
    {
        return array_values($this->container);
    }

    /**
     * Collect all of the elements of this iterable into a read-only list.
     * @see IterableOf::toList()
     */
    public function toList(): ListOf
    {
        if ($this instanceof ListOf && !($this instanceof MutableListOf)) {
            return $this;
        }

        $collection = new ArrayList($this->getType());
        $collection->container = $this->container;
        return $collection;
    }

    /**
     * Collect all of the elements of this iterable into a read-only list.
     * @see IterableOf::toMutableList()
     */
    public function toMutableList(): MutableListOf
    {
        if ($this instanceof MutableListOf) {
            return $this;
        }

        $collection = new MutableArrayList($this->getType());
        $collection->container = $this->container;
        return $collection;
    }

    /**
     * Collect all of the elements of this iterable into a read-only set.
     * @see IterableOf::toSet()
     */
    public function toSet(): Set
    {
        if ($this instanceof MutableSet) {
            $collection = new ArraySet($this->getType());
            $collection->container = $this->container;
            return $collection;
        } elseif ($this instanceof Set) {
            return $this;
        }

        return setOf($this->getType(), $this->container);
    }

    /**
     * Collect all of the elements of this iterable into a mutable set.
     * @see IterableOf::toMutableSet()
     */
    public function toMutableSet(): MutableSet
    {
        if ($this instanceof MutableSet) {
            return $this;
        } elseif ($this instanceof Set) {
            $collection = new MutableArraySet($this->getType());
            $collection->container = $this->container;
            return $collection;
        }

        return mutableSetOf($this->getType(), $this->container);
    }
}
