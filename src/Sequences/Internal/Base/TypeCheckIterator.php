<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;

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

    public function getType(): Type
    {
        return $this->type;
    }

    public function asIteration(): TypedIteration
    {
        return new IteratorIteration($this->type, $this);
    }

    public function current()
    {
        $value = $this->iterator->current();

        TypeAssurance::ensureContainedElementValue(
            $this->type,
            get_class($this->iterator) . ' iterator',
            $this->index,
            $value
        );
        return $value;
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
