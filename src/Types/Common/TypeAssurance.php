<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Common;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
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
                $type,
                Types::fromValue($value),
                $typeWrapper
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
                $type,
                $other,
                $typeWrapper
            );
        }
    }

    private static function createTypeError(
        int $argument,
        Type $expected,
        Type $provided,
        string $typeWrapper
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

        $expectedTypeName = $expected->getName();
        if (strlen($typeWrapper) > 0) {
            $expectedTypeName = $typeWrapper . ' of ' . $expectedTypeName;
        }
        if ($expected->acceptsNull()) {
            $expectedTypeName .= ' or null';
        }

        $providedTypeName = $provided->getName();
        if (strlen($typeWrapper) > 0) {
            $providedTypeName = $typeWrapper . ' of ' . $providedTypeName;
        }
        if ($provided->acceptsNull()) {
            $providedTypeName .= ' or null';
        }

        throw new TypeError(
            sprintf(
                "Argument %d passed to %s() must be of type %s, %s given, called in %s on line %d",
                $argument,
                $methodName,
                $expectedTypeName,
                $providedTypeName,
                $sourceTrace['file'],
                $sourceTrace['line']
            )
        );
    }
}
