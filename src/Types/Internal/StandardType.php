<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;
use Closure;

/**
 * {@see Type} representation of non-scalar types that are part of the PHP runtime.
 * @internal
 */
final class StandardType extends AbstractType implements Type
{
    public const ARRAY = 'array';
    public const RESOURCE = 'resource';
    public const OBJECT = 'object';
    public const CALLABLE = 'callable';

    /**
     * @var Closure
     */
    private $valueCheck;

    public function __construct(string $name, bool $nullable)
    {
        parent::__construct($name, false, $name !== 'array', $name === 'array', $nullable);
        $this->valueCheck = Closure::fromCallable(sprintf('is_%s', $name));
    }

    public function containsValue($value): bool
    {
        return is_null($value) && $this->acceptsNull() || ($this->valueCheck)($value);
    }

    public function containsType(Type $type): bool
    {
        if ($type instanceof NothingType) {
            return true;
        }
        return $type->getName() === $this->getName() && ($this->acceptsNull() || !$type->acceptsNull());
    }
}
