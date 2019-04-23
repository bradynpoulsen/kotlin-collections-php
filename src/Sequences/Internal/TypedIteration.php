<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;

/**
 * Simplified interface compared to PHP core's {@see Iterator}.
 * @internal
 */
interface TypedIteration
{
    /**
     * Retrieve the type provided this operator.
     * The type MAY become unavailable once the iteration has completed, but MUST be available so long that
     * {@see TypedIteration::hasNext()} returns true.
     *
     * @throws InvalidStateException if the type is not available
     */
    public function getType(): Type;

    /**
     * Returns `true` if the iteration has more elements.
     */
    public function hasNext(): bool;

    /**
     * Returns the next element in the iteration.
     * @throws NoSuchElementException if the iteration has been depleted.
     */
    public function next();
}
