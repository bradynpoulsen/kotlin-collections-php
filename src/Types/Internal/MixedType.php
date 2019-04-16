<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see Type} representation of an unchecked PHP type.
 * @internal
 */
final class MixedType extends AbstractType implements Type
{
    public function __construct()
    {
        parent::__construct('mixed', false, true, true, true);
    }

    /**
     * @param mixed $value
     * @return bool
     * @see Type::containsValue()
     */
    public function containsValue($value): bool
    {
        return true;
    }

    /**
     * @param Type $type
     * @return bool
     * @see Type::containsType()
     */
    public function containsType(Type $type): bool
    {
        return true;
    }
}
