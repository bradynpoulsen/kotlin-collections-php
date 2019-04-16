<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see Type} representation of a PHP scalar type.
 * @internal
 */
final class ScalarType extends AbstractType implements Type
{
    public const STRING = 'string';
    public const INTEGER = 'integer';
    public const FLOAT = 'double';
    public const BOOLEAN = 'boolean';

    public function __construct(string $name, bool $nullable)
    {
        parent::__construct($name, true, false, false, $nullable);
    }

    /**
     * @param mixed $value
     * @return bool
     * @see Type::containsValue()
     */
    public function containsValue($value): bool
    {
        return is_null($value) === $this->acceptsNull() || gettype($value) === $this->getName();
    }

    /**
     * @param Type $type
     * @return bool
     * @see Type::containsType()
     */
    public function containsType(Type $type): bool
    {
        return $type->getName() === $this->getName() && ($this->acceptsNull() || !$type->acceptsNull());
    }
}
