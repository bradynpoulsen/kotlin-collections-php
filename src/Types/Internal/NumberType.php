<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
final class NumberType extends AbstractType
{
    public function __construct(bool $acceptsNull)
    {
        parent::__construct('number', true, true, false, $acceptsNull);
    }

    public function containsValue($value): bool
    {
        return is_null($value) && $this->acceptsNull() || is_float($value) || is_int($value);
    }

    public function containsType(Type $type): bool
    {
        if ($type instanceof NothingType) {
            return true;
        }
        return ($type->getName() === ScalarType::FLOAT || $type->getName() === ScalarType::INTEGER)
            && ($this->acceptsNull() || !$type->acceptsNull());
    }
}
