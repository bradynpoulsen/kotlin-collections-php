<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\ConstrainedOnceSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;
use Traversable;

/**
 * @internal
 */
final class IteratorSequence extends AbstractBaseSequence
{
    /**
     * @var TypeCheckIterator
     */
    private $iterator;

    public static function create(Type $type, Iterator $iterator): Sequence
    {
        return new ConstrainedOnceSequence(new self($type, $iterator));
    }

    private function __construct(Type $type, Iterator $iterator)
    {
        parent::__construct($type);
        $this->iterator = new TypeCheckIterator($type, $iterator);
    }

    public function getIteration(): TypedIteration
    {
        $iteration = $this->iterator->asIteration();
        $this->iterator = null;
        return $iteration;
    }

    public function getIterator(): Traversable
    {
        $iterator = $this->iterator;
        $this->iterator = null;
        return $iterator;
    }
}
