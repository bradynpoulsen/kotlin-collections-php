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
    public static function ensureContainedArgumentValue(
        Type $type,
        int $argument,
        $value,
        string $typeWrapper = ''
    ): void {
        if (!$type->containsValue($value)) {
            throw self::createArgumentTypeError(
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
    public static function ensureContainedArgumentType(
        Type $type,
        int $argument,
        Type $other,
        string $typeWrapper = ''
    ): void {
        if (!$type->containsType($other)) {
            throw self::createArgumentTypeError(
                $argument,
                $type,
                $other,
                $typeWrapper
            );
        }
    }

    /**
     * Element validation helper to ensure that the specified $value is contained in the specified $expectedType.
     *
     * @param Type $expectedType
     * @param string $containerName
     * @param int $position
     * @param mixed $value
     */
    public static function ensureContainedElementValue(
        Type $expectedType,
        string $containerName,
        int $position,
        $value
    ): void {
        if (!$expectedType->containsValue($value)) {
            throw self::createElementTypeError($position, $expectedType, $containerName, Types::fromValue($value));
        }
    }

    /**
     * Element validation helper to ensure that the specified $providedType is contained in the specified $expectedType.
     *
     * @param Type $expectedType
     * @param string $containerName
     * @param int $position
     * @param Type $providedType
     */
    public static function ensureContainedElementType(
        Type $expectedType,
        string $containerName,
        int $position,
        Type $providedType
    ): void {
        if (!$expectedType->containsType($providedType)) {
            throw self::createElementTypeError($position, $expectedType, $containerName, $providedType);
        }
    }

    private static function createArgumentTypeError(
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
                $argument === 0 ? '$this' : $argument,
                $methodName,
                $expectedTypeName,
                $providedTypeName,
                $sourceTrace['file'],
                $sourceTrace['line']
            )
        );
    }

    private static function createElementTypeError(
        int $position,
        Type $expected,
        string $containerName,
        Type $provided
    ): TypeError {
        $expectedTypeName = $expected->getName();
        if ($expected->acceptsNull()) {
            $expectedTypeName .= ' or null';
        }

        $providedTypeName = $provided->getName();
        if ($provided->acceptsNull()) {
            $providedTypeName .= ' or null';
        }

        throw new TypeError(
            sprintf(
                'Element from %s at position %d must be of type %s, %s given',
                $containerName,
                $position,
                $expectedTypeName,
                $providedTypeName
            )
        );
    }
}
