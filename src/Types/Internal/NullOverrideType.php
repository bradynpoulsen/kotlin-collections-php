<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Common\TypeAssuranceTrait;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see Type} representation of modified nullability of another type.
 * @internal
 */
final class NullOverrideType implements Type
{
    /**
     * @var Type
     */
    private $delegate;
    /**
     * @var bool
     */
    private $acceptsNull;

    public function __construct(Type $delegate, bool $acceptsNull)
    {
        $this->delegate = $delegate;
        $this->acceptsNull = $acceptsNull;
    }

    public function acceptsNull(): bool
    {
        return $this->acceptsNull;
    }

    public function containsValue($value): bool
    {
        if (is_null($value)) {
            return $this->acceptsNull();
        }
        return $this->delegate->containsValue($value);
    }

    public function containsType(Type $type): bool
    {
        return ($this->acceptsNull() || !$type->acceptsNull()) && $this->delegate->containsType($type);
    }

    public function getName(): string
    {
        return $this->delegate->getName();
    }

    public function isScalar(): bool
    {
        return $this->delegate->isScalar();
    }

    public function isCompound(): bool
    {
        return $this->delegate->isCompound();
    }

    public function acceptsDynamicArray(): bool
    {
        return $this->delegate->acceptsDynamicArray();
    }
}
