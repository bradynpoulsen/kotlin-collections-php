<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Types\Type;
use Iterator;
use TypeError;

/**
 * @internal
 */
final class TypeCheckIterator implements Iterator
{
    /**
     * @var Type
     */
    private $type;

    /**
     * @var Iterator
     */
    private $iterator;

    /**
     * @var int
     */
    private $index = 0;

    public function __construct(Type $type, Iterator $iterator)
    {
        $this->type = $type;
        $this->iterator = $iterator;
    }

    public function current()
    {
        $value = $this->iterator->current();
        if ($this->type->containsValue($value)) {
            return $value;
        }

        $typeName = $this->type->getName();
        if ($this->type->acceptsNull()) {
            $typeName .= ' or null';
        }

        throw new TypeError(sprintf(
            'Element from %s iterator at position %d must be of type %s, %s given',
            get_class($this->iterator),
            $this->index,
            $typeName,
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    public function next(): void
    {
        $this->index++;
        $this->iterator->next();
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->index = 0;
        $this->iterator->rewind();
    }
}
