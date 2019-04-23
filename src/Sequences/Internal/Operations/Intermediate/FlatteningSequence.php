<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractDisposableIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractLinkedIterationSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;

/**
 * @internal
 */
final class FlatteningSequence extends AbstractLinkedIterationSequence
{
    public function __construct(Sequence $previous, Type $type)
    {
        TypeAssurance::ensureContainedArgumentType(
            Types::instance(Sequence::class),
            1,
            $previous->getType(),
            Sequence::class
        );
        parent::__construct($previous, $type);
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration
        {
            /**
             * @var TypedIteration|null
             */
            private $itemIteration = null;

            protected function computeNext(): void
            {
                if ($this->itemIteration !== null) {
                    if ($this->itemIteration->hasNext()) {
                        $this->setNext($this->itemIteration->next());
                        return;
                    }
                    $this->itemIteration = null;
                }

                while ($this->itemIteration === null && $this->getOperation()->hasNext()) {
                    $element = $this->getOperation()->next();
                    assert($element instanceof Sequence);
                    TypeAssurance::ensureContainedElementType(
                        $this->getSequence()->getType(),
                        'contained sequence',
                        $this->getNextPosition(),
                        $element->getType()
                    );

                    $subIteration = SequenceIteration::fromSequence($element);
                    if ($subIteration->hasNext()) {
                        $this->itemIteration = $subIteration;
                        $this->setNext($this->itemIteration->next());
                        return;
                    }
                }

                $this->done();
            }


            private function getSequence(): FlatteningSequence
            {
                return $this->getOperation()->getSequence();
            }
        };
    }
}
