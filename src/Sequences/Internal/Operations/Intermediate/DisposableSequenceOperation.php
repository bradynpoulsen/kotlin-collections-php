<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Sequences\Internal\LinkedSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * Reference container to allow better garbage collection by allowing operators to have an indirect reference.
 * @internal
 */
final class DisposableSequenceOperation implements TypedIteration
{
    /**
     * @var bool
     */
    private $disposed = false;

    /**
     * @var Sequence
     */
    private $sequence;

    /**
     * @var TypedIteration
     */
    private $iteration;

    public function __construct(LinkedSequence $sequence)
    {
        $this->sequence = $sequence;
        $this->iteration = SequenceIteration::fromSequence($sequence->getPrevious());
    }

    public function dispose(): void
    {
        $this->disposed = true;
        $this->sequence = null;
        $this->iteration = null;
    }

    /**
     * Partially dispose by dropping the reference to the sequence.
     */
    public function disposeSequence(): void
    {
        $this->sequence = null;
    }

    /**
     * @return Sequence
     */
    public function getSequence(): Sequence
    {
        if ($this->disposed || null === $this->sequence) {
            // @codeCoverageIgnoreStart
            throw new InvalidStateException("Sequence has been disposed!");
            // @codeCoverageIgnoreEnd
        }
        return $this->sequence;
    }

    /**
     * @return TypedIteration
     */
    public function getIteration(): TypedIteration
    {
        if ($this->disposed) {
            // @codeCoverageIgnoreStart
            throw new InvalidStateException("Operation has been disposed!");
            // @codeCoverageIgnoreEnd
        }
        return $this->iteration;
    }

    /**
     * Alias of {@see TypedIteration::getType()} obtained via {@see DisposableSequenceOperation::getIteration()}.
     */
    public function getType(): Type
    {
        return $this->getIteration()->getType();
    }

    /**
     * Alias of {@see TypedIteration::hasNext()} obtained via {@see DisposableSequenceOperation::getIteration()}.
     */
    public function hasNext(): bool
    {
        return $this->getIteration()->hasNext();
    }

    /**
     * Alias of {@see TypedIteration::next()} obtained via {@see DisposableSequenceOperation::getIteration()}.
     */
    public function next()
    {
        return $this->getIteration()->next();
    }
}
