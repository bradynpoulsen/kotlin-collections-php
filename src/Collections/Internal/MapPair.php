<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\MapEntry;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * {@see MapEntry} implementation.
 * @internal
 */
class MapPair implements MapEntry
{
    /**
     * @var Type
     */
    private $keyType;

    /**
     * @var mixed
     */
    private $key;

    /**
     * @var Type
     */
    private $valueType;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(Type $keyType, $key, Type $valueType, $value)
    {
        TypeAssurance::ensureContainedValue($keyType, 2, $key);
        TypeAssurance::ensureContainedValue($valueType, 4, $value);
        $this->keyType = $keyType;
        $this->key = $key;
        $this->valueType = $valueType;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s(%s, %s)',
            static::class,
            $this->getKey(),
            $this->getValue()
        );
    }

    /**
     * @return Type
     * @see MapEntry::getKeyType()
     */
    public function getKeyType(): Type
    {
        return $this->keyType;
    }

    /**
     * @return Type
     * @see MapEntry::getValueType()
     */
    public function getValueType(): Type
    {
        return $this->valueType;
    }

    /**
     * @return mixed
     * @see MapEntry::getKey()
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     * @see MapEntry::getValue()
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $newValue
     * @return mixed
     * @see MapEntry::getValueType()
     */
    protected function setValue($newValue)
    {
        TypeAssurance::ensureContainedValue($this->getValueType(), 1, $newValue);
        $current = $this->value;
        $this->value = $newValue;
        return $current;
    }
}
