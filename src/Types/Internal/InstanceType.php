<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\Types\Common\TypeAssuranceTrait;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see Type} representation of an object of a specific class or interface.
 * @internal
 */
final class InstanceType extends AbstractType implements Type
{
    use TypeAssuranceTrait;

    public function __construct(string $className, bool $nullable)
    {
        if (!class_exists($className) && !interface_exists($className)) {
            throw new InvalidArgumentException(sprintf("Class or interface not found: %s", $className));
        }
        parent::__construct($className, false, false, false, $nullable);
    }

    /**
     * @param mixed $value
     * @return bool
     * @see Type::isCovariantValue()
     */
    public function containsValue($value): bool
    {
        if (is_null($value)) {
            return $this->acceptsNull();
        }

        return get_class($value) === $this->getName() || is_subclass_of($value, $this->getName());
    }

    /**
     * @param Type $type
     * @return bool
     * @see Type::isCovariantType()
     */
    public function containsType(Type $type): bool
    {
        if ($type->acceptsNull() && !$this->acceptsNull()) {
            return false;
        }

        return $this->getName() === $type->getName() || is_subclass_of($type->getName(), $this->getName());
    }
}
