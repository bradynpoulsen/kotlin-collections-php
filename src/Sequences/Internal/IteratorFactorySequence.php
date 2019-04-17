<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Common\SequenceOperatorsTrait;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Type;
use Iterator;
use Traversable;
use TypeError;

/**
 * @internal
 */
final class IteratorFactorySequence implements Sequence
{
    use SequenceOperatorsTrait;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var callable
     */
    private $factory;

    public function __construct(Type $type, callable $factory)
    {
        $this->type = $type;
        $this->factory = $factory;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getIterator(): Traversable
    {
        $iterator = call_user_func($this->factory);
        if ($iterator instanceof Iterator) {
            return new TypeCheckIterator($this->type, $iterator);
        }

        throw new TypeError(sprintf(
            'Iterator returned by factory %s must be of type %s, %s given',
            $this->callableToString($this->factory),
            Iterator::class,
            is_object($iterator) ? get_class($iterator) : gettype($iterator)
        ));
    }

    private function callableToString(callable $factory): string
    {
        if (is_string($factory)) {
            return $factory;
        } elseif (is_array($factory)) {
            list($target, $method) = $factory;
            if (is_object($target)) {
                return sprintf('%s->%s', $this->objectToString($target), $method);
            } elseif (is_string($target)) {
                return sprintf('%s::%s', $target, $method);
            }
        }

        return is_object($factory) ? $this->objectToString($factory) : gettype($factory);
    }

    private function objectToString(object $obj): string
    {
        return sprintf('%s#%s', get_class($obj), spl_object_id($obj));
    }
}
