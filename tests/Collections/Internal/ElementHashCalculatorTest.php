<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\ElementHashCalculator
 */
class ElementHashCalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function calculate_scalarsAndNull(): void
    {
        self::assertSame(serialize(null), ElementHashCalculator::calculate(null));
        self::assertSame(serialize("foo"), ElementHashCalculator::calculate("foo"));
        self::assertSame(serialize(100), ElementHashCalculator::calculate(100));
        self::assertSame(serialize(10.0), ElementHashCalculator::calculate(10.0));
        self::assertSame(serialize(true), ElementHashCalculator::calculate(true));
    }

    /**
     * @test
     */
    public function calculate_resource(): void
    {
        $file = tmpfile();
        self::assertSame(sprintf('res:stream:%d;', (int)$file), ElementHashCalculator::calculate($file));
    }

    /**
     * @test
     */
    public function calculate_object_closure(): void
    {
        $func = function () {
        };
        self::assertSame(sprintf('obj:Closure:%d;', spl_object_id($func)), ElementHashCalculator::calculate($func));
    }

    /**
     * @test
     */
    public function calculate_object_withNamespace(): void
    {
        self::assertSame(
            sprintf(
                'obj:BradynPoulsen\Kotlin\Collections\Internal\ElementHashCalculatorTest:%d;',
                spl_object_id($this)
            ),
            ElementHashCalculator::calculate($this)
        );
    }

    /**
     * @test
     */
    public function calculate_string_viaStaticCallable(): void
    {
        self::assertSame(
            's:81:"BradynPoulsen\Kotlin\Collections\Internal\ElementHashCalculatorTest::staticMethod";',
            ElementHashCalculator::calculate(ElementHashCalculatorTest::class . '::staticMethod')
        );
    }

    /**
     * @test
     */
    public function calculate_callable_viaArrayStatic(): void
    {
        self::assertSame(
            'fun:BradynPoulsen\Kotlin\Collections\Internal\ElementHashCalculatorTest::staticMethod;',
            ElementHashCalculator::calculate([ElementHashCalculatorTest::class, 'staticMethod'])
        );
    }

    /**
     * @test
     */
    public function calculate_callable_viaArrayObjectMethod(): void
    {
        self::assertSame(
            sprintf(
                'fun:obj:BradynPoulsen\Kotlin\Collections\Internal\ElementHashCalculatorTest:%d::instanceMethod;',
                spl_object_id($this)
            ),
            ElementHashCalculator::calculate([$this, 'instanceMethod'])
        );
    }

    /**
     * @test
     */
    public function calculate_array_sequentialArray(): void
    {
        self::assertSame(
            'arr:3:{i:0;s:3:"foo";i:1;s:3:"bar";i:2;s:3:"baz";};',
            ElementHashCalculator::calculate(["foo", "bar", "baz"])
        );
    }

    /**
     * @test
     */
    public function calculate_array_associativeArray(): void
    {
        self::assertSame(
            'arr:1:{s:3:"foo";s:3:"bar";};',
            ElementHashCalculator::calculate(['foo' => 'bar'])
        );
    }

    public static function staticMethod(): void
    {
        self::fail("Method should not be called");
    }

    public function instanceMethod(): void
    {
        self::fail("Method should not be called");
    }
}
