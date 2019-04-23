<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
interface IterationSequence extends Sequence
{
    public function getIteration(): TypedIteration;
}
