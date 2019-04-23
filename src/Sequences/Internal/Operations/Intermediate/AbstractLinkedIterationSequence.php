<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\LinkedSequence;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
abstract class AbstractLinkedIterationSequence extends AbstractIterationSequence implements LinkedSequence
{
    /**
     * @var Sequence
     */
    private $previous;

    protected function __construct(Sequence $previous, ?Type $type = null)
    {
        $this->previous = $previous;
        parent::__construct($type ?? $previous->getType());
    }

    protected function clearPrevious(): void
    {
        $this->previous = null;
    }

    protected function isPreviousCleared(): bool
    {
        return null === $this->previous;
    }

    /**
     * @return Sequence
     */
    public function getPrevious(): Sequence
    {
        return $this->previous;
    }
}
