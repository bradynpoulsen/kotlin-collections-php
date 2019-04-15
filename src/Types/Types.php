<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

use BradynPoulsen\Kotlin\Types\Internal\InstanceType;
use BradynPoulsen\Kotlin\Types\Internal\MixedType;
use BradynPoulsen\Kotlin\Types\Internal\NothingType;
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

    public static function string(): Type
    {
        return new ScalarType(ScalarType::STRING, false);
    }

    public static function stringOrNull(): Type
    {
        return new ScalarType(ScalarType::STRING, true);
    }

    public static function integer(): Type
    {
        return new ScalarType(ScalarType::INTEGER, false);
    }

    public static function integerOrNull(): Type
    {
        return new ScalarType(ScalarType::INTEGER, true);
    }

    public static function float(): Type
    {
        return new ScalarType(ScalarType::FLOAT, false);
    }

    public static function floatOrNull(): Type
    {
        return new ScalarType(ScalarType::FLOAT, true);
    }

    public static function boolean(): Type
    {
        return new ScalarType(ScalarType::BOOLEAN, false);
    }

    public static function booleanOrNull(): Type
    {
        return new ScalarType(ScalarType::BOOLEAN, true);
    }

    public static function arrayOf(): Type
    {
        return new StandardType(StandardType::ARRAY, false);
    }

    public static function arrayOrNull(): Type
    {
        return new StandardType(StandardType::ARRAY, true);
    }

    public static function object(): Type
    {
        return new StandardType(StandardType::OBJECT, false);
    }

    public static function objectOrNull(): Type
    {
        return new StandardType(StandardType::OBJECT, true);
    }

    public static function resource(): Type
    {
        return new StandardType(StandardType::RESOURCE, false);
    }

    public static function resourceOrNull(): Type
    {
        return new StandardType(StandardType::RESOURCE, true);
    }

    public static function callableOf(): Type
    {
        return new StandardType(StandardType::CALLABLE, false);
    }

    public static function callableOrNull(): Type
    {
        return new StandardType(StandardType::CALLABLE, true);
    }

    public static function instance(string $type): Type
    {
        return new InstanceType($type, false);
    }

    public static function instanceOrNull(string $type): Type
    {
        return new InstanceType($type, true);
    }

    public static function mixed(): Type
    {
        return new MixedType();
    }

    public static function nothing(): Type
    {
        return new NothingType(false);
    }

    public static function nothingOrNull(): Type
    {
        return new NothingType(true);
    }

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
