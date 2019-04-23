<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIteration;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;

/**
 * @internal
 */
final class IteratorIteration extends AbstractIteration
{
    /**
     * @var TypeCheckIterator
     */
    private $iterator;

    /**
     * @var bool
     */
    private $started = false;

    public function __construct(Type $type, Iterator $iterator)
    {
        if (!($iterator instanceof TypeCheckIterator)) {
            $iterator = new TypeCheckIterator($type, $iterator);
        }
        $this->iterator = $iterator;
        parent::__construct($type);
    }

    protected function computeNext(): void
    {
        if ($this->started) {
            $this->iterator->next();
        } else {
            $this->started = true;
            $this->iterator->rewind();
        }

        if ($this->iterator->valid()) {
            $this->setNext($this->iterator->current());
            return;
        }

        $this->iterator = null;
        $this->done();
    }
}
