<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use BradynPoulsen\Kotlin\Collections\Internal\StringType;
use function BradynPoulsen\Kotlin\Collections\mutableListOf;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Common\CollectionContainsTrait
 */
class CollectionContainsTraitTest extends TestCase
{
    /**
     * @test
     */
    public function containsInArray(): void
    {
        $list = mutableListOf(new StringType(), ["foo"]);

        self::assertTrue($list->contains("foo"));
        self::assertFalse($list->contains("bar"));
    }

    /**
     * @test
     */
    public function containsAllInArray(): void
    {
        $list = mutableListOf(new StringType(), ["foo", "bar", "baz"]);

        $otherList = mutableListOf(new StringType(), ["bar"]);
        $anotherList = mutableListOf(new StringType(), ["bar"]);

        self::assertTrue($list->containsAll($list));
        self::assertTrue($list->containsAll($otherList));
        self::assertTrue($list->containsAll($anotherList));
        self::assertTrue($otherList->containsAll($anotherList));
        self::assertTrue($anotherList->containsAll($otherList));
        self::assertFalse($otherList->containsAll($list));
        self::assertFalse($anotherList->containsAll($list));
    }

    /**
     * @test
     */
    public function containsAnyInArray(): void
    {
        $list = mutableListOf(new StringType(), ["foo", "bar", "baz"]);

        $otherList = mutableListOf(new StringType(), ["foo"]);
        $anotherList = mutableListOf(new StringType(), ["baz"]);

        self::assertTrue($list->containsAny($list));
        self::assertTrue($list->containsAny($otherList));
        self::assertTrue($list->containsAny($anotherList));
        self::assertFalse($otherList->containsAny($anotherList));
        self::assertFalse($anotherList->containsAny($otherList));
        self::assertTrue($otherList->containsAny($list));
        self::assertTrue($anotherList->containsAny($list));
    }
}
