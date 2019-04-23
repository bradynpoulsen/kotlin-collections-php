<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIterationSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Types;
use function BradynPoulsen\Kotlin\pair;

/**
 * @internal
 */
final class ZippingSequence extends AbstractIterationSequence
{
    /**
     * @var Sequence
     */
    private $first;

    /**
     * @var Sequence
     */
    private $second;

    public function __construct(Sequence $first, Sequence $second)
    {
        parent::__construct(Types::instance(Pair::class));
        $this->first = $first;
        $this->second = $second;
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractIteration {
            /**
             * @var TypedIteration
             */
            private $firstIteration;

            /**
             * @var TypedIteration
             */
            private $secondIteration;

            public function __construct(ZippingSequence $sequence)
            {
                parent::__construct($sequence->getType());
                $this->firstIteration = SequenceIteration::fromSequence($sequence->getFirst());
                $this->secondIteration = SequenceIteration::fromSequence($sequence->getSecond());
            }

            protected function computeNext(): void
            {
                if ($this->firstIteration->hasNext() && $this->secondIteration->hasNext()) {
                    $this->setNext(pair($this->firstIteration->next(), $this->secondIteration->next()));
                    return;
                }

                $this->done();
            }

            protected function done(): void
            {
                parent::done();
                $this->firstIteration = null;
                $this->secondIteration = null;
            }
        };
    }

    /**
     * @return Sequence
     */
    public function getFirst(): Sequence
    {
        return $this->first;
    }

    /**
     * @return Sequence
     */
    public function getSecond(): Sequence
    {
        return $this->second;
    }
}
