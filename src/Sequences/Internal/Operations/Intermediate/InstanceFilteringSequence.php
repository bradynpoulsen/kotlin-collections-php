<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractLinkedIterationSequence;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\EmptySequence;
use BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use Traversable;

/**
 * @internal
 */
final class InstanceFilteringSequence extends AbstractLinkedIterationSequence
{
    private function __construct(Sequence $source, Type $newType)
    {
        parent::__construct($source, $newType);
    }

    public function getIteration(): TypedIteration
    {
        return SequenceIteration::fromSequence($this->getPrevious());
    }

    public static function filterIsInstanceUpdatedType(Sequence $source, Type $targetType): Sequence
    {
        if ($source->getType()->containsType($targetType)) {
            return new self($source->filter([$targetType, 'containsValue']), $targetType);
        }

        return new EmptySequence($targetType);
    }

    public static function filterIsNotInstanceUpdatedType(Sequence $source, Type $targetType): Sequence
    {
        // short path: sequence contract g
        if ($source->getType()->containsType($targetType)) {
            return $source->filterNot([$targetType, 'containsValue']);
        }

        return $source;
    }

    public static function filterNotNullUpdatedType(Sequence $source): Sequence
    {
        return new self($source->filterNot(function ($it): bool {
            return $it === null;
        }), Types::notNullable($source->getType()));
    }
}
