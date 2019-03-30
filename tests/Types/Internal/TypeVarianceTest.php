<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Types\Internal\TypeVariance
 */
class TypeVarianceTest extends TestCase
{
    /**
     * @test
     */
    public function invalidVarianceName()
    {
        $this->expectException(InvalidArgumentException::class);
        TypeVariance::toVarianceName(PHP_INT_MAX);
    }
}
