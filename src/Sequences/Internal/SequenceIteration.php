<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
final class SequenceIteration
{
    public static function fromSequence(Sequence $sequence): TypedIteration
    {
        if ($sequence instanceof IterationSequence) {
            return $sequence->getIteration();
        }

        /** @noinspection PhpParamsInspection */
        return new IteratorIteration($sequence->getType(), $sequence->getIterator());
    }
}
