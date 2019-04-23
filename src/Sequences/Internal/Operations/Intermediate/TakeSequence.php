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
final class TakeSequence extends AbstractLinkedIterationSequence implements DropTakeSequence
{
    /**
     * @var int
     */
    private $count;

    public function __construct(Sequence $source, int $count)
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive");
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
        if ($count >= $this->count) {
            return new EmptySequence($this->getType());
        }
        return new SubSequence($this->getPrevious(), $count, $this->count - 1);
    }

    /**
     * @see Sequence::take()
     */
    public function take(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must be positive.");
        }
        if ($count === 0) {
            return new EmptySequence($this->getType());
        }
        if ($count >= $this->count) {
            return $this;
        }
        return new TakeSequence($this->getPrevious(), $count);
    }

    public function getIteration(): TypedIteration
    {
        return new class($this, $this->count) extends AbstractDisposableIteration {
            /**
             * @var int
             */
            private $left;

            public function __construct(TakeSequence $source, int $left)
            {
                parent::__construct($source);
                $this->getOperation()->disposeSequence();
                $this->left = $left;
            }

            protected function computeNext(): void
            {
                if ($this->left > 0 && $this->getOperation()->hasNext()) {
                    $this->left -= 1;
                    $this->setNext($this->getOperation()->next());
                    return;
                }

                $this->done();
            }
        };
    }
}
