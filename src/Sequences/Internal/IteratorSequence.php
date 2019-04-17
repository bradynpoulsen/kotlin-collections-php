<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Common\SequenceOperatorsTrait;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;
use Traversable;

/**
 * @internal
 */
final class IteratorSequence implements Sequence
{
    use SequenceOperatorsTrait;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var Iterator
     */
    private $iterator;

    private function __construct(Type $type, Iterator $iterator)
    {
        $this->iterator = $iterator;
        $this->type = $type;
    }

    public static function create(Type $type, Iterator $iterator): Sequence
    {
        return (new self($type, $iterator))->constrainOnce();
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getIterator(): Traversable
    {
        return new TypeCheckIterator($this->type, $this->iterator);
    }
}
