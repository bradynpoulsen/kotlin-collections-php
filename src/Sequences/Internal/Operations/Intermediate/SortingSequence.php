<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use ArrayIterator;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\ReverseArrayIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
final class SortingSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var callable|null
     */
    private $comparator;

    /**
     * @var bool
     */
    private $ascending;

    public function __construct(Sequence $previous, ?callable $comparator = null, bool $ascending = true)
    {
        parent::__construct($previous);
        $this->comparator = $comparator;
        $this->ascending = $ascending;
    }

    public function getIteration(): TypedIteration
    {
        $elements = iterator_to_array($this->getPrevious());

        if (null === $this->comparator) {
            sort($elements, SORT_REGULAR);
        } else {
            usort($elements, $this->comparator);
        }

        if (!$this->ascending) {
            return new ReverseArrayIteration($this->getType(), $elements);
        }

        return new IteratorIteration($this->getType(), new ArrayIterator($elements));
    }
}
