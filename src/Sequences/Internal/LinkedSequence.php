<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
interface LinkedSequence extends Sequence
{
    /**
     * Get the previous sequence in this chain.
     * If this sequence is constrained to be consumed only once, the previous sequence MAY become unavailable.
     */
    public function getPrevious(): Sequence;
}
