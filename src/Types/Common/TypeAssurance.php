<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Common;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Types\Type;
use TypeError;

/**
 * Validation helpers to ensure that only valid values/types are utilized in context of a specific type.
 */
final class TypeAssurance
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Argument validation helper to ensure that the specified $value is the same type or a subtype.
     * @param Type $type
     * @param int $argument
     * @param mixed $value
     * @param string $typeWrapper If specified, gives more context on a wrapping container of the type (ie, Collection).
     *
     * @see TypeAssuranceTrait::ensureValue()
     */
    public static function ensureContainedValue(Type $type, int $argument, $value, string $typeWrapper = ''): void
    {
        if (!$type->containsValue($value)) {
            throw self::createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                $type->acceptsNull(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '')
                . (is_object($value) ? get_class($value) : strtolower(gettype($value)))
            );
        }
    }

    /**
     * Argument validation helper to ensure that the specified $type is the same type or a subtype.
     * @param Type $type
     * @param int $argument
     * @param Type $other
     * @param string $typeWrapper If specified, gives more context on a wrapping container of the type (ie, Collection).
     *
     * @see TypeAssuranceTrait::ensureType()
     */
    public static function ensureContainedType(Type $type, int $argument, Type $other, string $typeWrapper = ''): void
    {
        if (!$type->containsType($other)) {
            throw self::createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $type->getName(),
                $type->acceptsNull(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $other->getName(),
                $other->acceptsNull()
            );
        }
    }

    private static function createTypeError(
        int $argument,
        string $expectedType,
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
                "Argument %d passed to %s() must be of type %s, %s given, called in %s on line %d",
                $argument,
                $methodName,
                $expectedType . ($expectedNullable ? ' or null' : ''),
                $providedType . ($providedNullable ? ' or null' : ''),
                $sourceTrace['file'],
                $sourceTrace['line']
            )
        );
    }
}
