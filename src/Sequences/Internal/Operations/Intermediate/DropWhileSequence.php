<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractDisposableIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractLinkedIterationSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
final class DropWhileSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var callable
     */
    private $predicate;
    /**
     * @var bool
     */
    private $dropWhile;

    public function __construct(Sequence $previous, callable $predicate, bool $dropWhile = true)
    {
        parent::__construct($previous);
        $this->predicate = $predicate;
        $this->dropWhile = $dropWhile;
    }

    /**
     * @return callable
     */
    public function getPredicate(): callable
    {
        return $this->predicate;
    }

    /**
     * @return bool
     */
    public function isDropWhile(): bool
    {
        return $this->dropWhile;
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration
        {
            private $dropping = true;

            private function getSequence(): DropWhileSequence
            {
                return $this->getOperation()->getSequence();
            }

            protected function computeNext(): void
            {
                if (!$this->dropping) {
                    if ($this->getOperation()->hasNext()) {
                        $this->setNext($this->getOperation()->next());
                        return;
                    }
                } else {
                    while ($this->getOperation()->hasNext()) {
                        $item = $this->getOperation()->next();
                        if ($this->getSequence()->isDropWhile() !== (bool)call_user_func(
                            $this->getSequence()->getPredicate(),
                            $item
                        )) {
                            $this->dropping = false;
                            $this->setNext($item);
                            return;
                        }
                    }
                }

                $this->done();
            }
        };
    }
}
