<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see Type} representation of a value that never exists.
 * @see https://kotlinlang.org/api/latest/jvm/stdlib/kotlin/-nothing.html
 * @internal
 */
final class NothingType extends AbstractType implements Type
{
    public function __construct(bool $acceptsNull)
    {
        parent::__construct('nothing', false, false, false, $acceptsNull);
    }

    public function containsValue($value): bool
    {
        return is_null($value) && $this->acceptsNull();
    }

    public function containsType(Type $type): bool
    {
        return $type instanceof NothingType && ($this->acceptsNull() || !$type->acceptsNull());
    }
}
