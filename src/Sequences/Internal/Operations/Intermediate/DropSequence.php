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
final class DropSequence extends AbstractLinkedIterationSequence implements DropTakeSequence
{
    /**
     * @var int
     */
    private $count;

    public function __construct(Sequence $source, int $count)
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive.");
        }

        parent::__construct($source);
        $this->count = $count;
    }

    /**
     * @see Sequence::drop()
     */
    public function drop(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive.");
        }
        if ($count === 0) {
            return $this;
        }

        $newCount = $this->count + $count;
        return new DropSequence($this->getPrevious(), $newCount);
    }

    /**
     * @see Sequence::take()
     */
    public function take(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive.");
        } elseif ($count === 0) {
            return new EmptySequence($this->getType());
        }
        return new SubSequence($this->getPrevious(), $this->count, $this->count + $count - 1);
    }

    public function getIteration(): TypedIteration
    {
        return new class($this, $this->count) extends AbstractDisposableIteration {
            /**
             * Amount of elements remaining to be skipped
             * @var int
             */
            private $left;

            public function __construct(DropSequence $sequence, int $left)
            {
                parent::__construct($sequence);
                $this->getOperation()->disposeSequence();
                $this->left = $left;
            }

            protected function computeNext(): void
            {
                while ($this->left > 0 && $this->getOperation()->hasNext()) {
                    $this->left -= 1;
                    $this->getOperation()->next();
                    $this->markSkipped();
                }

                if ($this->left > 0 || !$this->getOperation()->hasNext()) {
                    $this->done();
                    return;
                }

                $this->setNext($this->getOperation()->next());
            }
        };
    }
}
