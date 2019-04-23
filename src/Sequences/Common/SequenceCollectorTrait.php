<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;

use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\Collections\MutableSet;
use BradynPoulsen\Kotlin\Collections\Set;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Sequences\Sequences;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use function BradynPoulsen\Kotlin\Collections\mutableListOf;
use function BradynPoulsen\Kotlin\Collections\mutableSetOf;

trait SequenceCollectorTrait
{
    public function toArray(array &$target = []): array
    {
        assert($this instanceof Sequence);
        foreach ($this as $element) {
            array_push($target, $element);
        }
        return $target;
    }

    public function toList(?MutableListOf $target = null): ListOf
    {
        assert($this instanceof Sequence);
        if ($target === null) {
            $target = mutableListOf($this->getType());
        }

        Sequences::collectTo($this, $target);
        return $target->toList();
    }

    public function toSet(?MutableSet $target = null): Set
    {
        assert($this instanceof Sequence);
        if ($target === null) {
            $target = mutableSetOf($this->getType());
        }

        Sequences::collectTo($this, $target);
        return $target->toSet();
    }
}
