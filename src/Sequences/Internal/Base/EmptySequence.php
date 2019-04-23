<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\EmptyIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Types\Type;
use EmptyIterator;
use Traversable;

/**
 * @internal
 */
final class EmptySequence extends AbstractBaseSequence
{
    public function __construct(Type $type)
    {
        parent::__construct($type);
    }

    public function getIteration(): TypedIteration
    {
        return new EmptyIteration();
    }

    public function getIterator(): Traversable
    {
        return new EmptyIterator();
    }
}
