<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
final class TransformingSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var callable
     */
    private $transformer;
    /**
     * @var bool
     */
    private $includeIndex;

    public function __construct(Sequence $previous, Type $newType, callable $transformer, bool $includeIndex = false)
    {
        parent::__construct($previous, $newType);
        $this->transformer = $transformer;
        $this->includeIndex = $includeIndex;
    }

    public function getIteration(): TypedIteration
    {
        return new class($this) extends AbstractDisposableIteration
        {
            protected function computeNext(): void
            {
                if ($this->getOperation()->hasNext()) {
                    $transformArgs = [$this->getOperation()->next()];
                    if ($this->getSequence()->isIncludeIndex()) {
                        array_unshift($transformArgs, $this->getNextPosition());
                    }
                    $element = call_user_func_array(
                        $this->getSequence()->getTransformer(),
                        $transformArgs
                    );

                    TypeAssurance::ensureContainedElementValue(
                        $this->getSequence()->getType(),
                        'transforming function',
                        $this->getNextPosition(),
                        $element
                    );
                    $this->setNext($element);
                    return;
                }

                $this->done();
            }

            private function getSequence(): TransformingSequence
            {
                return $this->getOperation()->getSequence();
            }
        };
    }

    /**
     * @return callable
     */
    public function getTransformer(): callable
    {
        return $this->transformer;
    }

    /**
     * @return bool
     */
    public function isIncludeIndex(): bool
    {
        return $this->includeIndex;
    }
}
