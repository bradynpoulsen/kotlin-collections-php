<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Types\Type;
use Iterator;
use Traversable;
use TypeError;

/**
 * @internal
 */
final class IteratorFactorySequence extends AbstractBaseSequence
{
    /**
     * @var callable
     */
    private $factory;

    public function __construct(Type $type, callable $factory)
    {
        parent::__construct($type);
        $this->factory = $factory;
    }

    public function getIterator(): Traversable
    {
        $iterator = call_user_func($this->factory);
        if ($iterator instanceof TypeCheckIterator) {
            return $iterator;
        } elseif ($iterator instanceof Iterator) {
            return new TypeCheckIterator($this->getType(), $iterator);
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
            return $factory . '()';
        } elseif (is_array($factory)) {
            list($target, $method) = $factory;
            if (is_object($target)) {
                return sprintf('%s->%s()', $this->objectToString($target), $method);
            } elseif (is_string($target)) {
                return sprintf('%s::%s()', $target, $method);
            }
        }

        /** @noinspection PhpParamsInspection */
        return is_object($factory) ? $this->objectToString($factory) : gettype($factory);
    }

    private function objectToString(object $obj): string
    {
        return sprintf('%s#%s', get_class($obj), spl_object_id($obj));
    }
}
