<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Collections\Internal\MutableHashedSet;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Types;

/**
 * @internal
 */
final class DistinctSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var callable (T) -> R
     */
    private $selector;

    public function __construct(Sequence $source, ?callable $selector = null)
    {
        parent::__construct($source);
        $this->selector = $selector ?? function ($it) {
            return $it;
        };
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration {
            /**
             * @var MutableHashedSet
             */
            private $observed;

            public function __construct(DistinctSequence $sequence)
            {
                $this->observed = new MutableHashedSet(Types::mixed());
                parent::__construct($sequence);
            }

            protected function computeNext(): void
            {
                while ($this->getOperation()->hasNext()) {
                    $element = $this->getOperation()->next();
                    $key = call_user_func($this->getSequence()->getSelector(), $element);

                    if ($this->observed->add($key)) {
                        $this->setNext($element);
                        return;
                    }
                }

                $this->done();
            }

            private function getSequence(): DistinctSequence
            {
                return $this->getOperation()->getSequence();
            }
        };
    }

    public function getSelector(): callable
    {
        return $this->selector;
    }
}
