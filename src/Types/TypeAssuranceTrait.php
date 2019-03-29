<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

use BradynPoulsen\Kotlin\InvalidStateException;
use TypeError;

/**
 * @internal
 */
trait TypeAssuranceTrait
{
    /**
     * Type-safe way to ensure this trait is used on an instance of Type
     */
    private function thisType(): Type
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this;
    }

    /**
     * Argument validation helper to ensure that the specified $value is covariant of this type.
     * @param int $argument
     * @param mixed|null $value
     *
     * @throws TypeError if the specified $value is not covariant
     * @see Type::ensureCovariantValue()
     */
    public function ensureCovariantValue(int $argument, $value): void
    {
        $type = $this->thisType();
        if (!$type->isCovariantValue($value)) {
            throw $this->createTypeError(
                $argument,
                $type->getName(),
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
     *
     * @throws TypeError if the specified $value is not contravariant
     * @see Type::ensureContravariantValue()
     */
    public function ensureContravariantValue(int $argument, $value): void
    {
        $type = $this->thisType();
        if (!$type->isContravariantValue($value)) {
            throw $this->createTypeError(
                $argument,
                $type->getName(),
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
     *
     * @throws TypeError if the specified $value is not invariant.
     * @see Type::ensureInvariantValue()
     */
    public function ensureInvariantValue(int $argument, $value): void
    {
        $type = $this->thisType();
        if (!$type->isInvariantValue($value)) {
            throw $this->createTypeError(
                $argument,
                $type->getName(),
                TypeVariance::INVARIANT,
                $type->isNullable(),
                is_object($value) ? get_class($value) : strtolower(gettype($value))
            );
        }
    }

    private function createTypeError(
        int $argument,
        string $expectedType,
        int $expectedVariance,
        bool $expectedNullable,
        string $providedType
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
                "Argument %d passed to %s() must be of %s %s%s, %s given, called in %s on line %d",
                $argument,
                $methodName,
                TypeVariance::toVarianceName($expectedVariance),
                $expectedType,
                $expectedNullable ? ' or null' : '',
                $providedType,
                $sourceTrace['file'],
                $sourceTrace['line']
            )
        );
    }
}
