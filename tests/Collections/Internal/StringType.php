<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Types\Common\TypeAssuranceTrait;
use BradynPoulsen\Kotlin\Types\Type;

class StringType implements Type
{
    public function getName(): string
    {
        return 'string';
    }

    public function isScalar(): bool
    {
        return true;
    }

    public function isCompound(): bool
    {
        return false;
    }

    public function acceptsDynamicArray(): bool
    {
        return false;
    }

    public function acceptsNull(): bool
    {
        return false;
    }

    public function containsValue($value): bool
    {
        return is_string($value);
    }

    public function containsType(Type $type): bool
    {
        return $type instanceof StringType;
    }
}
