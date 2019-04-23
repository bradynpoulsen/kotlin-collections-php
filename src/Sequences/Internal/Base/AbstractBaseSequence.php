<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIterationSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;

/**
 * @internal
 */
abstract class AbstractBaseSequence extends AbstractIterationSequence
{
    public function getIteration(): TypedIteration
    {
        /** @noinspection PhpParamsInspection */
        return new IteratorIteration($this->getType(), $this->getIterator());
    }
}
