<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Common;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Types\Type;
use TypeError;

/**
 * Implementation for ensure* methods of {@see Type}.
 */
trait TypeAssuranceTrait
{
    /**
     * @param int $argument
     * @param mixed $value
     * @param string $typeWrapper
     * @throws TypeError
     * @see Type::ensureValue()
     */
    public function ensureValue(int $argument, $value, string $typeWrapper = ''): void
    {
        assert($this instanceof Type);
        if (!$this->containsValue($value)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $this->getName(),
                $this->acceptsNull(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '')
                    . (is_object($value) ? get_class($value) : strtolower(gettype($value)))
            );
        }
    }

    /**
     * @param int $argument
     * @param Type $other
     * @param string $typeWrapper
     * @throws TypeError
     * @see Type::ensureCovariantType()
     */
    public function ensureType(int $argument, Type $other, string $typeWrapper = ''): void
    {
        assert($this instanceof Type);
        if (!$this->containsType($other)) {
            throw $this->TypeAssuranceTrait_createTypeError(
                $argument,
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $this->getName(),
                $this->acceptsNull(),
                (strlen($typeWrapper) > 0 ? $typeWrapper . ' of ' : '') . $other->getName(),
                $other->acceptsNull()
            );
        }
    }

    private function TypeAssuranceTrait_createTypeError(
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
