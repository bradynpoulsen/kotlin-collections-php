<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\LinkedSequence;

/**
 * @internal
 */
abstract class AbstractDisposableIteration extends AbstractIteration
{
    /**
     * @var DisposableSequenceOperation
     */
    private $operation;

    public function __construct(LinkedSequence $sequence)
    {
        $this->operation = new DisposableSequenceOperation($sequence);
        parent::__construct($sequence->getType());
    }

    /**
     * @return DisposableSequenceOperation
     */
    final protected function getOperation(): DisposableSequenceOperation
    {
        return $this->operation;
    }

    protected function done(): void
    {
        $this->operation->dispose();
        parent::done();
    }
}
