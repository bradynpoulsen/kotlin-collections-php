<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
final class EmptyIteration implements TypedIteration
{
    public function getType(): Type
    {
        throw new InvalidStateException("Type is not available. Iteration is depleted");
    }

    public function hasNext(): bool
    {
        return false;
    }

    public function next()
    {
        throw new NoSuchElementException("Iteration has depleted.");
    }
}
