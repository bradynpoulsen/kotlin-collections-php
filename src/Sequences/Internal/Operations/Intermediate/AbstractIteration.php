<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
abstract class AbstractIteration implements TypedIteration
{
    private const STATE_NOT_READY = 0;
    private const STATE_READY = 1;
    private const STATE_DONE = 2;
    private const STATE_FAILED = 3;

    private $state = self::STATE_NOT_READY;
    private $nextValue = null;
    private $nextPosition = -1;

    /**
     * Computes the next item of this iteration.
     *
     * This callback method should call one of these two methods:
     *  - {@see AbstractIteration::setNext()} with the next value of the iteration
     *  - {@see AbstractIteration::done()} to indicate there are no more elements
     *
     * Failing to call one of these methods results in a failed iteration.
     *
     * If elements from a source iteration are skipped, {@see AbstractIteration::markSkipped()} should
     * be called for each skipped element to maintain {@see AbstractIteration::getNextPosition()}.
     */
    abstract protected function computeNext(): void;

    /**
     * @var Type
     */
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    final public function getType(): Type
    {
        return $this->type;
    }

    final public function hasNext(): bool
    {
        switch ($this->state) {
            case self::STATE_FAILED:
                throw new InvalidStateException("Iteration has failed!");
            case self::STATE_DONE:
                return false;
            case self::STATE_READY:
                return true;
            default:
                return $this->tryComputeNext();
        }
    }

    final public function next()
    {
        if (!$this->hasNext()) {
            throw new NoSuchElementException();
        }
        $this->state = self::STATE_NOT_READY;
        $value = $this->nextValue;
        $this->nextValue = null;
        return $value;
    }

    private function tryComputeNext(): bool
    {
        $this->state = self::STATE_FAILED;
        $this->nextPosition++;
        $this->computeNext();
        return $this->state === self::STATE_READY;
    }

    final protected function getNextPosition(): int
    {
        return $this->nextPosition;
    }

    final protected function markSkipped(): void
    {
        $this->nextPosition++;
    }

    final protected function setNext($value): void
    {
        $this->nextValue = $value;
        $this->state = self::STATE_READY;
    }

    protected function done(): void
    {
        $this->state = self::STATE_DONE;
    }
}
