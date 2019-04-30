<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * Internal implementation of {@see Sequence::filter()} and {@see Sequence::filterNot()} operations.
 * @internal
 */
final class FilteringSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var callable
     */
    private $predicate;

    /**
     * @var bool
     */
    private $sendWhen;

    /**
     * @var bool
     */
    private $includeIndex;

    public function __construct(Sequence $previous, callable $predicate, bool $sendWhen, bool $includeIndex = false)
    {
        parent::__construct($previous);
        $this->predicate = $predicate;
        $this->sendWhen = $sendWhen;
        $this->includeIndex = $includeIndex;
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration
        {
            protected function computeNext(): void
            {
                while ($this->getOperation()->hasNext()) {
                    $element = $this->getOperation()->next();
                    $filterArgs = [$element];
                    if ($this->getSequence()->isIncludeIndex()) {
                        array_unshift($filterArgs, $this->getNextPosition());
                    }

                    if ($this->getSequence()->isSendWhen() === (bool)call_user_func_array(
                        $this->getSequence()->getPredicate(),
                        $filterArgs
                    )) {
                        $this->setNext($element);
                        return;
                    }
                    $this->markSkipped();
                }

                $this->done();
            }

            private function getSequence(): FilteringSequence
            {
                return $this->getOperation()->getSequence();
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
    public function isSendWhen(): bool
    {
        return $this->sendWhen;
    }

    /**
     * @return bool
     */
    public function isIncludeIndex(): bool
    {
        return $this->includeIndex;
    }
}
