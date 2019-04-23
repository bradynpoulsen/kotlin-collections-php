<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use function BradynPoulsen\Kotlin\Collections\listOf;

/**
 * Creates an empty {@see Sequence} that cannot contain any items.
 * @return Sequence Sequence<nothing>
 */
function emptySequence(): Sequence
{
    return new EmptySequence(Types::nothing());
}

/**
 * @param Type $type Type<T>
 * @param callable $iteratorFactory () -> Iterator<T>
 * @return Sequence Sequence<T>
 */
function sequence(Type $type, callable $iteratorFactory): Sequence
{
    return Sequences::iteratorFactory($type, $iteratorFactory);
}

/**
 * @param Type $type Type<T>
 * @param array $values array<T>
 * @return Sequence Sequence<T>
 */
function sequenceOf(Type $type, array $values): Sequence
{
    return listOf($type, $values)->asSequence();
}
