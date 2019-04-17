<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;


use BradynPoulsen\Kotlin\Sequences\Internal\ConstrainedOnceSequence;
use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * Common implementations of {@see Sequence} operators to allow new operators to be added without a breaking change.
 */
trait SequenceOperatorsTrait
{
    /**
     * @see Sequence::constrainOnce
     */
    public function constrainOnce(): Sequence
    {
        assert($this instanceof Sequence);
        return new ConstrainedOnceSequence($this);
    }
}
