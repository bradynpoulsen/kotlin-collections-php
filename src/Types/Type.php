<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

use BradynPoulsen\Kotlin\Types\Common\TypeAssuranceTrait;
use TypeError;

interface Type
{
    /**
     * Get the name of this type. For class types, the name MUST be fully-qualified.
     */
    public function getName(): string;

    /**
     * @return bool `true` if this type is a scalar value, `false` otherwise.
     */
    public function isScalar(): bool;

    /**
     * @return bool `true` if this type is a union of two or more types, `false` otherwise.
     */
    public function isCompound(): bool;

    /**
     * @return bool `true` if this type accepts a dynamically-sized array as a value, `false` otherwise.
     */
    public function acceptsDynamicArray(): bool;

    /**
     * @return bool `true` if this type accepts `null` as a value, `false` otherwise.
     */
    public function acceptsNull(): bool;

    /**
     * @param $value
     * @return bool `true` if the specified $value is the same type or a subtype.
     */
    public function containsValue($value): bool;

    /**
     * @param Type $type
     * @return bool `true` if the specified $type is the same type or a subtype.
     */
    public function containsType(Type $type): bool;

    /**
     * Argument validation helper to ensure that the specified $value is the same type or a subtype.
     * @param int $argument
     * @param mixed $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not the same type or a subtype.
     * @see TypeAssuranceTrait::ensureValue()
     */
    public function ensureValue(int $argument, $value, string $typeWrapper = ''): void;

    /**
     * Argument validation helper to ensure that the specified $type is the same type or a subtype.
     * @param int $argument
     * @param Type $type
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not the same type or a subtype.
     * @see TypeAssuranceTrait::ensureType()
     */
    public function ensureType(int $argument, Type $type, string $typeWrapper = ''): void;
}
