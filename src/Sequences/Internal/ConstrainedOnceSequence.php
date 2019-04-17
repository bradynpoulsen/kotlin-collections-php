<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Sequences\Common\SequenceOperatorsTrait;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use Traversable;

/**
 * @internal
 */
final class ConstrainedOnceSequence implements Sequence
{
    use SequenceOperatorsTrait;

    /**
     * @var Sequence|null
     */
    private $delegate;

    /**
     * @var Type
     */
    private $type;

    public function __construct(Sequence $delegate)
    {
        $this->delegate = $delegate;
        $this->type = $delegate->getType();
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getIterator(): Traversable
    {
        if (null === $this->delegate) {
            throw new InvalidStateException("Cannot iterate Sequence; it has been constrained to one time");
        }

        $iterator = $this->delegate->getIterator();
        $this->delegate = null;
        return $iterator;
    }

    /**
     * @see Sequence::constrainOnce()
     */
    public function constrainOnce(): Sequence
    {
        return $this;
    }
}
