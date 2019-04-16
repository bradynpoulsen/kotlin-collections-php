<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin;

final class Pair
{
    /**
     * @var mixed
     */
    private $first;

    /**
     * @var mixed
     */
    private $second;

    public function __construct($first, $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    public function __toString(): string
    {
        return sprintf('%s(%s, %s)', self::class, $this->getFirst(), $this->getSecond());
    }

    /**
     * @return mixed
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return mixed
     */
    public function getSecond()
    {
        return $this->second;
    }
}
