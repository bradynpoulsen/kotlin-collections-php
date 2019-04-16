<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use BradynPoulsen\Kotlin\Collections\Internal\StringType;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Collections\listOf;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Common\ListIndexOfTrait
 */
class ListIndexOfTraitTest extends TestCase
{
    /**
     * @test
     */
    public function containsIndex(): void
    {
        $list = listOf(new StringType(), ["foo"]);

        self::assertTrue($list->containsIndex(0));
        self::assertFalse($list->containsIndex(-1));
        self::assertFalse($list->containsIndex(1));
        self::assertFalse($list->containsIndex(PHP_INT_MIN));
        self::assertFalse($list->containsIndex(PHP_INT_MAX));
    }

    /**
     * @test
     */
    public function indexOfFirst(): void
    {
        $list = listOf(new StringType(), ["foo", "bar", "baz"]);
        self::assertSame(1, $list->indexOfFirst("bar"));
    }

    /**
     * @test
     */
    public function indexOfFirstMissing(): void
    {
        $list = listOf(new StringType());
        self::assertNull($list->indexOfFirst("foo"));
    }

    /**
     * @test
     */
    public function indexOfLast(): void
    {
        $list = listOf(new StringType(), ["foo", "foo", "bar", "bar", "baz", "baz"]);
        self::assertSame(3, $list->indexOfLast("bar"));
    }

    /**
     * @test
     */
    public function indexOfLastMissing(): void
    {
        $list = listOf(new StringType());
        self::assertNull($list->indexOfLast("foo"));
    }
}
