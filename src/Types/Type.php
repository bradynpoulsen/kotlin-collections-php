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
     * Argument validation helper to ensure that the specified $value is covariant of this type.
     * @param int $argument
     * @param mixed|null $value
     *
     * @throws TypeError if the specified $value is not covariant.
     * @see TypeAssuranceTrait::ensureCovariantValue()
     */
    public function ensureCovariantValue(int $argument, $value): void;

    /**
     * Argument validation helper to ensure that the specified $value is contravariant of this type.
     * @param int $argument
     * @param mixed|null $value
     *
     * @throws TypeError if the specified $value is not contravariant.
     * @see TypeAssuranceTrait::ensureContravariantValue()
     */
    public function ensureContravariantValue(int $argument, $value): void;

    /**
     * Argument validation helper to ensure that the specified $value is invariant of this type.
     * @param int $argument
     * @param mixed|null $value
     *
     * @throws TypeError if the specified $value is not invariant.
     * @see TypeAssuranceTrait::ensureInvariantValue()
     */
    public function ensureInvariantValue(int $argument, $value): void;
}
