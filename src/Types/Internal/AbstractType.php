<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
abstract class AbstractType implements Type
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $isScalar;
    /**
     * @var bool
     */
    private $isCompound;
    /**
     * @var bool
     */
    private $acceptsDynamicArray;
    /**
     * @var bool
     */
    private $acceptsNull;

    protected function __construct(string $name, bool $isScalar, bool $isCompound, bool $acceptsDynamicArray, bool $acceptsNull)
    {
        $this->name = $name;
        $this->isScalar = $isScalar;
        $this->isCompound = $isCompound;
        $this->acceptsDynamicArray = $acceptsDynamicArray;
        $this->acceptsNull = $acceptsNull;
    }

    public function __toString(): string
    {
        return sprintf('%s%s', $this->acceptsNull() ? '?' : '', $this->getName());
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isScalar(): bool
    {
        return $this->isScalar;
    }

    public function isCompound(): bool
    {
        return $this->isCompound;
    }

    public function acceptsDynamicArray(): bool
    {
        return $this->acceptsDynamicArray;
    }

    public function acceptsNull(): bool
    {
        return $this->acceptsNull;
    }
}
