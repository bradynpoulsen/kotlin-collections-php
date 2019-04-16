<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\UnsupportedOperationException;

/**
 * @internal
 */
final class ElementHashCalculator
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Calculates a serialization hash for the specified $element.
     *
     * This method is identical to {@see serialize()} with the following adjustments:
     *  1) resources are serialized as `res:${type}:${id};`
     *  2) objects are serialized as `obj:${type}:${id};`
     *  3) callables are serialized as `fun:${target}::${method};` where target is calculated, too.
     *  4) arrays are serialized as `arr:${size}:{${keyN};${valueN};};` where each key and value is calculated, too.
     *
     * @param $element
     * @return string
     * @throws UnsupportedOperationException if the specified $element is not a type that is supported.
     */
    public static function calculate($element): string
    {
        if (is_null($element) || is_scalar($element)) {
            return serialize($element);
        } elseif (is_resource($element)) {
            return sprintf('res:%s:%d;', get_resource_type($element), (int)$element);
        } elseif (is_object($element)) {
            return sprintf('obj:%s:%d;', get_class($element), spl_object_id($element));
        } elseif (is_callable($element)) {
            return self::calculateCallable($element);
        } elseif (is_array($element)) {
            return self::calculateArray($element);
        }
        // This exception is only thrown if a new base type is added to PHP core
        // @codeCoverageIgnoreStart
        throw new UnsupportedOperationException(
            sprintf(
                "Cannot determine a unique hash for element of type %s",
                is_object($element) ? get_class($element) : gettype($element)
            )
        );
        // @codeCoverageIgnoreEnd
    }

    private static function calculateCallable(callable $element): string
    {
        $identifier = null;
        if (is_array($element)) {
            $target = $element[0];
            $method = $element[1];
            if (is_object($target)) {
                $target = rtrim(self::calculate($target), ';');
            }
            $identifier = sprintf('%s::%s', $target, $method);
        }

        if (!is_null($identifier)) {
            return sprintf('fun:%s;', $identifier);
        }

        // This exception is only thrown if a new form of callable is introduce into the PHP language
        // @codeCoverageIgnoreStart
        throw new UnsupportedOperationException(sprintf(
            "Failed to determine unique hash of callable for type %s",
            is_object($element) ? get_class($element) : gettype($element)
        ));
        // @codeCoverageIgnoreEnd
    }

    private static function calculateArray(array $element): string
    {
        $serializedElements = '';
        foreach ($element as $key => $value) {
            $serializedElements .= self::calculate($key);
            $serializedElements .= self::calculate($value);
        }
        return sprintf('arr:%d:{%s};', count($element), $serializedElements);
    }
}
