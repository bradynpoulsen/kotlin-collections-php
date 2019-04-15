<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

use BradynPoulsen\Kotlin\Types\Internal\InstanceType;
use BradynPoulsen\Kotlin\Types\Internal\MixedType;
use BradynPoulsen\Kotlin\Types\Internal\NothingType;
use BradynPoulsen\Kotlin\Types\Internal\NullOverrideType;
use BradynPoulsen\Kotlin\Types\Internal\ScalarType;
use BradynPoulsen\Kotlin\Types\Internal\StandardType;
use BradynPoulsen\Kotlin\UnsupportedOperationException;

final class Types
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Type representation of a non-null string.
     * @return Type
     */
    public static function string(): Type
    {
        return new ScalarType(ScalarType::STRING, false);
    }

    /**
     * Type representation of a nullable string.
     * @return Type
     */
    public static function stringOrNull(): Type
    {
        return new ScalarType(ScalarType::STRING, true);
    }

    /**
     * Type representation of a non-null integer.
     * @return Type
     */
    public static function integer(): Type
    {
        return new ScalarType(ScalarType::INTEGER, false);
    }

    /**
     * Type representation of a nullable integer.
     * @return Type
     */
    public static function integerOrNull(): Type
    {
        return new ScalarType(ScalarType::INTEGER, true);
    }

    /**
     * Type representation of a non-null float.
     * @return Type
     */
    public static function float(): Type
    {
        return new ScalarType(ScalarType::FLOAT, false);
    }

    /**
     * Type representation of a nullable float.
     * @return Type
     */
    public static function floatOrNull(): Type
    {
        return new ScalarType(ScalarType::FLOAT, true);
    }

    /**
     * Type representation of a non-null boolean.
     * @return Type
     */
    public static function boolean(): Type
    {
        return new ScalarType(ScalarType::BOOLEAN, false);
    }

    /**
     * Type representation of a nullable boolean.
     * @return Type
     */
    public static function booleanOrNull(): Type
    {
        return new ScalarType(ScalarType::BOOLEAN, true);
    }

    /**
     * Type representation of a non-null array.
     * @return Type
     */
    public static function arrayOf(): Type
    {
        return new StandardType(StandardType::ARRAY, false);
    }

    /**
     * Type representation of a nullable array.
     * @return Type
     */
    public static function arrayOrNull(): Type
    {
        return new StandardType(StandardType::ARRAY, true);
    }

    /**
     * Type representation of a non-null object.
     * @return Type
     */
    public static function object(): Type
    {
        return new StandardType(StandardType::OBJECT, false);
    }

    /**
     * Type representation of a nullable object.
     * @return Type
     */
    public static function objectOrNull(): Type
    {
        return new StandardType(StandardType::OBJECT, true);
    }

    /**
     * Type representation of a non-null resource.
     * @return Type
     */
    public static function resource(): Type
    {
        return new StandardType(StandardType::RESOURCE, false);
    }

    /**
     * Type representation of a nullable resource.
     * @return Type
     */
    public static function resourceOrNull(): Type
    {
        return new StandardType(StandardType::RESOURCE, true);
    }

    /**
     * Type representation of a non-null callable.
     * @return Type
     */
    public static function callableOf(): Type
    {
        return new StandardType(StandardType::CALLABLE, false);
    }

    /**
     * Type representation of a nullable callable.
     * @return Type
     */
    public static function callableOrNull(): Type
    {
        return new StandardType(StandardType::CALLABLE, true);
    }

    /**
     * Type representation of a non-null instance of the specified $type.
     * @param string $type
     * @return Type
     */
    public static function instance(string $type): Type
    {
        return new InstanceType($type, false);
    }

    /**
     * Type representation of a nullable instance of the specified $type.
     * @param string $type
     * @return Type
     */
    public static function instanceOrNull(string $type): Type
    {
        return new InstanceType($type, true);
    }

    /**
     * An unsafe type representation of every possible type in PHP.
     * @return Type
     */
    public static function mixed(): Type
    {
        return new MixedType();
    }

    /**
     * Type representation of a value that never exists.
     * @return Type
     */
    public static function nothing(): Type
    {
        return new NothingType(false);
    }

    /**
     * Type representation of a nullable value that never exists.
     * @return Type
     */
    public static function nothingOrNull(): Type
    {
        return new NothingType(true);
    }

    /**
     * Type representation of a nullable variant of the specified $type.
     * @param Type $type
     * @return Type
     */
    public static function nullable(Type $type): Type
    {
        return $type->acceptsNull() ? $type : new NullOverrideType($type, true);
    }

    /**
     * Type representation of a non-nullable variant of the specified $type.
     * @param Type $type
     * @return Type
     */
    public static function notNullable(Type $type): Type
    {
        return !$type->acceptsNull() ? $type : new NullOverrideType($type, false);
    }

    /**
     * Type representation of specified $value.
     * @param mixed $value
     * @return Type
     * @throws UnsupportedOperationException when the specified $value is for an unknown type (part
     *      of untested future PHP versions).
     */
    public static function fromValue($value): Type
    {
        if (is_null($value)) {
            return self::nothingOrNull();
        } elseif (is_string($value)) {
            return self::string();
        } elseif (is_int($value)) {
            return self::integer();
        } elseif (is_float($value)) {
            return self::float();
        } elseif (is_bool($value)) {
            return self::boolean();
        } elseif (is_resource($value)) {
            return self::resource();
        } elseif (is_callable($value)) {
            return self::callableOf();
        } elseif (is_array($value)) {
            return self::arrayOf();
        } elseif (is_object($value)) {
            return self::instance(get_class($value));
        }

        throw new UnsupportedOperationException("Could not create Type for " . gettype($value) . " value.");
    }
}
