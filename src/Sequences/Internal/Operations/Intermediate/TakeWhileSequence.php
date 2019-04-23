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
final class TakeWhileSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var callable
     */
    private $predicate;

    /**
     * @var bool
     */
    private $sendWhile;

    public function __construct(Sequence $source, callable $predicate, bool $sendWhile = true)
    {
        parent::__construct($source);
        $this->predicate = $predicate;
        $this->sendWhile = $sendWhile;
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration
        {
            public function __construct(TakeWhileSequence $sequence)
            {
                parent::__construct($sequence);
            }

            public function getSequence(): TakeWhileSequence
            {
                return $this->getOperation()->getSequence();
            }

            protected function computeNext(): void
            {
                if ($this->getOperation()->hasNext()) {
                    $element = $this->getOperation()->next();
                    if ($this->getSequence()->isSendWhile() === (bool)call_user_func(
                        $this->getSequence()->getPredicate(),
                        $element
                    )) {
                        $this->setNext($element);
                        return;
                    }
                }

                $this->done();
            }
        };
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
    public function isSendWhile(): bool
    {
        return $this->sendWhile;
    }
}
