<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\IterationSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use Traversable;

/**
 * Internal implementation of {@see Sequence::constrainOnce()} operation.
 * @internal
 */
final class ConstrainedOnceSequence extends AbstractLinkedIterationSequence
{
    public function __construct(Sequence $delegate)
    {
        parent::__construct($delegate);
    }

    public function getIteration(): TypedIteration
    {
        if ($this->isPreviousCleared()) {
            throw new InvalidStateException("This sequence can only be consumed once.");
        }

        $previous = $this->getPrevious();
        if ($previous instanceof IterationSequence) {
            $iteration = $previous->getIteration();
        } else {
            /** @noinspection PhpParamsInspection */
            $iteration = new IteratorIteration($this->getPrevious()->getType(), $this->getPrevious()->getIterator());
        }

        $this->clearPrevious();
        return $iteration;
    }

    public function getIterator(): Traversable
    {
        if ($this->isPreviousCleared()) {
            throw new InvalidStateException("This sequence can only be consumed once.");
        }

        $iterator = $this->getPrevious()->getIterator();
        $this->clearPrevious();
        return $iterator;
    }

    /**
     * @see Sequence::constrainOnce()
     */
    public function constrainOnce(): Sequence
    {
        return $this;
    }
}
