<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

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
     * @return bool `true` if this type is a pseudo-type (union of two or more types), `false` otherwise.
     */
    public function isPseudo(): bool;

    /**
     * @return bool `true` if this type accepts `null` as a value, `false` otherwise.
     */
    public function isNullable(): bool;

    /**
     * @param mixed|null $value
     * @return bool `true` if the specified $value is covariant of this type.
     */
    public function isCovariantValue($value): bool;

    /**
     * @param mixed|null $value
     * @return bool `true` if the specified $value is contravariant of this type.
     */
    public function isContravariantValue($value): bool;

    /**
     * @param mixed|null $value
     * @return bool `true` if the specified $value is invariant of this type.
     */
    public function isInvariantValue($value): bool;

    /**
     * @param Type $type
     * @return bool `true` if the specified $type is covariant of this type.
     */
    public function isCovariantType(Type $type): bool;

    /**
     * @param Type $type
     * @return bool `true` if the specified $type is contravariant of this type.
     */
    public function isContravariantType(Type $type): bool;

    /**
     * @param Type $type
     * @return bool `true` if the specified $type is invariant of this type.
     */
    public function isInvariantType(Type $type): bool;

    /**
     * Argument validation helper to ensure that the specified $value is covariant of this type.
     * @param int $argument
     * @param mixed|null $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not covariant.
     * @see TypeAssuranceTrait::ensureCovariantValue()
     */
    public function ensureCovariantValue(int $argument, $value, string $typeWrapper = ''): void;

    /**
     * Argument validation helper to ensure that the specified $value is contravariant of this type.
     * @param int $argument
     * @param mixed|null $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not contravariant.
     * @see TypeAssuranceTrait::ensureContravariantValue()
     */
    public function ensureContravariantValue(int $argument, $value, string $typeWrapper = ''): void;

    /**
     * Argument validation helper to ensure that the specified $value is invariant of this type.
     * @param int $argument
     * @param mixed|null $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not invariant.
     * @see TypeAssuranceTrait::ensureInvariantValue()
     */
    public function ensureInvariantValue(int $argument, $value, string $typeWrapper = ''): void;

    /**
     * Argument validation helper to ensure that the specified $type is covariant of this type.
     * @param int $argument
     * @param Type $type
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not covariant.
     * @see TypeAssuranceTrait::ensureCovariantType()
     */
    public function ensureCovariantType(int $argument, Type $type, string $typeWrapper = ''): void;

    /**
     * Argument validation helper to ensure that the specified $type is contravariant of this type.
     * @param int $argument
     * @param Type $type
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not contravariant.
     * @see TypeAssuranceTrait::ensureContravariantType()
     */
    public function ensureContravariantType(int $argument, Type $type, string $typeWrapper = ''): void;

    /**
     * Argument validation helper to ensure that the specified $type is invariant of this type.
     * @param int $argument
     * @param Type $type
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not invariant.
     * @see TypeAssuranceTrait::ensureInvariantType()
     */
    public function ensureInvariantType(int $argument, Type $type, string $typeWrapper = ''): void;
}
