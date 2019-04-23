<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\DropTakeSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
final class SubSequence extends AbstractLinkedIterationSequence implements DropTakeSequence
{
    /**
     * The starting index, inclusive
     * @var int
     */
    private $startIndex;

    /**
     * The ending index, inclusive
     * @var int
     */
    private $endIndex;

    public function __construct(Sequence $source, int $startIndex, int $endIndex)
    {
        if ($startIndex < 0) {
            throw new InvalidArgumentException("startIndex $startIndex must be positive.");
        } elseif ($endIndex < 0) {
            throw new InvalidArgumentException("endIndex $endIndex must be positive.");
        } elseif ($endIndex < $startIndex) {
            throw new InvalidArgumentException("endIndex $endIndex cannot be less than startIndex $startIndex.");
        }

        parent::__construct($source);
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
    }

    public function drop(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive.");
        }
        if ($count === 0) {
            return $this;
        }
        if ($count > ($this->endIndex - $this->startIndex)) {
            return new EmptySequence($this->getType());
        }
        return new SubSequence($this->getPrevious(), $this->startIndex + $count, $this->endIndex);
    }

    public function take(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive.");
        }
        if ($count === 0) {
            return new EmptySequence($this->getType());
        }
        if ($count >= ($this->endIndex - $this->startIndex)) {
            return $this;
        }
        return new SubSequence($this->getPrevious(), $this->startIndex, $this->startIndex + $count - 1);
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration {
            protected function computeNext(): void
            {
                while (
                    $this->getNextPosition() < $this->getSequence()->getStartIndex()
                    && $this->getOperation()->hasNext()
                ) {
                    $this->getOperation()->next();
                    $this->markSkipped();
                }

                if (
                    $this->getNextPosition() > $this->getSequence()->getEndIndex()
                    || !$this->getOperation()->hasNext()
                ) {
                    $this->done();
                    return;
                }

                $this->setNext($this->getOperation()->next());
            }

            private function getSequence(): SubSequence
            {
                return $this->getOperation()->getSequence();
            }
        };
    }

    /**
     * @return int
     */
    public function getStartIndex(): int
    {
        return $this->startIndex;
    }

    /**
     * @return int
     */
    public function getEndIndex(): int
    {
        return $this->endIndex;
    }
}
