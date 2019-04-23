<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Common\SequenceCollectorTrait;
use BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait;
use BradynPoulsen\Kotlin\Sequences\Internal\IterationSequence;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use Traversable;

/**
 * @internal
 */
abstract class AbstractIterationSequence implements IterationSequence
{
    use SequenceCollectorTrait;
    use SequenceIntermediateOperationsTrait;

    /**
     * @var Type
     */
    private $type;

    protected function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return Type
     * @see Sequence::getType()
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return Traversable
     * @see Sequence::getIterator()
     */
    public function getIterator(): Traversable
    {
        $iteration = $this->getIteration();
        while ($iteration->hasNext()) {
            yield $iteration->next();
        }
    }
}
