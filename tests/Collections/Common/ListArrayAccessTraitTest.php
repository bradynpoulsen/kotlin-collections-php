<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use BradynPoulsen\Kotlin\Collections\Internal\StringType;
use BradynPoulsen\Kotlin\NoSuchElementException;
use PHPUnit\Framework\TestCase;
use Throwable;
use function BradynPoulsen\Kotlin\Collections\listOf;
use function BradynPoulsen\Kotlin\Collections\mutableListOf;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Common\ListArrayAccessTrait
 */
class ListArrayAccessTraitTest extends TestCase
{
    /**
     * @test
     */
    public function offsetExists(): void
    {
        $list = listOf(new StringType(), ["foo", "bar", "baz"]);

        self::assertFalse(isset($list[PHP_INT_MIN]));
        self::assertFalse(isset($list[PHP_INT_MAX]));
        self::assertFalse(isset($list[-1]));
        self::assertFalse(isset($list[3]));

        self::assertTrue(isset($list[0]));
        self::assertTrue(isset($list[1]));
        self::assertTrue(isset($list[2]));
    }

    /**
     * @test
     */
    public function offsetGet(): void
    {
        $list = listOf(new StringType(), ["foo", "bar", "baz"]);

        self::assertSame("foo", $list[0]);
        self::assertSame("bar", $list[1]);
        self::assertSame("baz", $list[2]);

        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            $list[PHP_INT_MIN];
        });
        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            $list[PHP_INT_MAX];
        });
        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            $list[-1];
        });
        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            $list[3];
        });
    }
    /**
     * @test
     */
    public function offsetSet(): void
    {
        $list = mutableListOf(new StringType(), ["foo"]);

        $list[0] = "bar";
        self::assertCount(1, $list);

        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            $list[-1] = "baz";
        });
        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            $list[1] = "baz";
        });
    }

    /**
     * @test
     */
    public function offsetUnset(): void
    {
        $list = mutableListOf(new StringType(), ["foo"]);

        unset($list[0]);
        self::assertCount(0, $list);

        self::assertThrows(NoSuchElementException::class, function () use ($list) {
            unset($list[0]);
        });
    }

    private static function assertThrows(string $type, callable $block)
    {
        try {
            $block();
            self::fail("Failed to assert that nothing thrown is of type $type");
        } catch (Throwable $throwable) {
            $caughtType = get_class($throwable);
            if ($caughtType !== $type && !is_subclass_of($caughtType, $type)) {
                self::fail("Failed to assert that thrown $caughtType is of type $type");
            }
        }
    }
}
