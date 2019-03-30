<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Types\Type;
use TypeError;

/**
 * Implementation for ensure* methods of {@see Type}.
 * @internal
 */
trait TypeAssuranceTrait
{
    /**
     * Type-safe way to ensure this trait is used on an instance of Type
     */
    private function TypeAssuranceTrait_this(): Type
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this;
    }

    /**
     * Argument validation helper to ensure that the specified $value is covariant of this type.
     * @param int $argument
     * @param mixed|null $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not covariant.
     * @see TypeAssuranceTrait::ensureCovariantValue()
     */
    public function ensureCovariantValue(int $argument, $value, string $typeWrapper = ''): void
    {
        $type = $this->TypeAssuranceTrait_this();
        if (!$type->isCovariantValue($value)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                TypeVariance::COVARIANT,
                $type->isNullable(),
                is_object($value) ? get_class($value) : strtolower(gettype($value))
            );
        }
    }

    /**
     * Argument validation helper to ensure that the specified $value is contravariant of this type.
     * @param int $argument
     * @param mixed|null $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not contravariant.
     * @see TypeAssuranceTrait::ensureContravariantValue()
     */
    public function ensureContravariantValue(int $argument, $value, string $typeWrapper = ''): void
    {
        $type = $this->TypeAssuranceTrait_this();
        if (!$type->isContravariantValue($value)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                TypeVariance::CONTRAVARIANT,
                $type->isNullable(),
                is_object($value) ? get_class($value) : strtolower(gettype($value))
            );
        }
    }

    /**
     * Argument validation helper to ensure that the specified $value is invariant of this type.
     * @param int $argument
     * @param mixed|null $value
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not invariant.
     * @see TypeAssuranceTrait::ensureInvariantValue()
     */
    public function ensureInvariantValue(int $argument, $value, string $typeWrapper = ''): void
    {
        $type = $this->TypeAssuranceTrait_this();
        if (!$type->isInvariantValue($value)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                TypeVariance::INVARIANT,
                $type->isNullable(),
                is_object($value) ? get_class($value) : strtolower(gettype($value))
            );
        }
    }

    /**
     * Argument validation helper to ensure that the specified $type is covariant of this type.
     * @param int $argument
     * @param Type $other
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not covariant.
     * @see TypeAssuranceTrait::ensureCovariantType()
     */
    public function ensureCovariantType(int $argument, Type $other, string $typeWrapper = ''): void
    {
        $type = $this->TypeAssuranceTrait_this();
        if (!$type->isCovariantType($other)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                TypeVariance::COVARIANT,
                $type->isNullable(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $other->getName(),
                $other->isNullable()
            );
        }
    }

    /**
     * Argument validation helper to ensure that the specified $type is contravariant of this type.
     * @param int $argument
     * @param Type $other
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not contravariant.
     * @see TypeAssuranceTrait::ensureContravariantType()
     */
    public function ensureContravariantType(int $argument, Type $other, string $typeWrapper = ''): void
    {
        $type = $this->TypeAssuranceTrait_this();
        if (!$type->isContravariantType($other)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                TypeVariance::CONTRAVARIANT,
                $type->isNullable(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $other->getName(),
                $other->isNullable()
            );
        }
    }

    /**
     * Argument validation helper to ensure that the specified $type is invariant of this type.
     * @param int $argument
     * @param Type $other
     * @param string $typeWrapper
     *
     * @throws TypeError if the specified $value is not invariant.
     * @see TypeAssuranceTrait::ensureInvariantType()
     */
    public function ensureInvariantType(int $argument, Type $other, string $typeWrapper = ''): void
    {
        $type = $this->TypeAssuranceTrait_this();
        if (!$type->isInvariantType($other)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                TypeVariance::INVARIANT,
                $type->isNullable(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $other->getName(),
                $other->isNullable()
            );
        }
    }

    private function TypeAssuranceTrait_createTypeError(
        int $argument,
        string $expectedType,
        int $expectedVariance,
        bool $expectedNullable,
        string $providedType,
        bool $providedNullable = false
    ): TypeError {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $sourceTrace = $backtrace[2] ?? null;

        if (!is_array($sourceTrace)) {
            // @codeCoverageIgnoreStart
            throw new InvalidStateException("Failed to identify caller via backtrace");
            // @codeCoverageIgnoreEnd
        }

        $methodName = $sourceTrace['function'];
        if ($sourceTrace['class']) {
            $methodName = $sourceTrace['class'] . '::' . $methodName;
        }

        throw new TypeError(
            sprintf(
                "Argument %d passed to %s() must be of %s %s, %s given, called in %s on line %d",
                $argument,
                $methodName,
                TypeVariance::toVarianceName($expectedVariance),
                $expectedType . ($expectedNullable ? ' or null' : ''),
                $providedType . ($providedNullable ? ' or null' : ''),
                $sourceTrace['file'],
                $sourceTrace['line']
            )
        );
    }
}
