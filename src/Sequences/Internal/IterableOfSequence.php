<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Collections\IterableOf;
use BradynPoulsen\Kotlin\Sequences\Common\SequenceOperatorsTrait;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use Traversable;

/**
 * @internal
 */
final class IterableOfSequence implements Sequence
{
    use SequenceOperatorsTrait;

    /**
     * @var IterableOf
     */
    private $iterable;

    public function __construct(IterableOf $iterable)
    {
        $this->iterable = $iterable;
    }

    public function getType(): Type
    {
        return $this->iterable->getType();
    }

    public function getIterator(): Traversable
    {
        return $this->iterable->getIterator();
    }
}
