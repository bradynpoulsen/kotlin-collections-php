<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Collections\IterableOf;
use Traversable;

/**
 * @internal
 */
final class IterableOfSequence extends AbstractBaseSequence
{
    /**
     * @var IterableOf
     */
    private $iterable;

    public function __construct(IterableOf $iterable)
    {
        parent::__construct($iterable->getType());
        $this->iterable = $iterable;
    }

    public function getIterator(): Traversable
    {
        return $this->iterable->getIterator();
    }
}
