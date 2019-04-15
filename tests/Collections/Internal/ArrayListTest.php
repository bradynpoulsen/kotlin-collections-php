<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use function BradynPoulsen\Kotlin\Collections\listOf;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\AbstractArrayCollection
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\ArrayList
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableArrayCollectionTrait
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableArrayList
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableListTrait
 */
class ArrayListTest extends TestCase
{
    /**
     * @test
     */
    public function maintainsOrder(): void
    {
        $list = new MutableArrayList(Types::string());
        $list->add("foo");
        $list->add("bar");
        $list->add("baz");

        self::assertSame(["foo", "bar", "baz"], $list->toArray());
    }

    /**
     * @test
     */
    public function basicCrudOperations(): void
    {
        $list = new MutableArrayList(Types::string());

        self::assertFalse($list->containsIndex(0));
        self::assertFalse($list->contains("foo"));

        self::assertTrue($list->add("foo"));
        self::assertTrue($list->containsIndex(0));
        self::assertTrue($list->contains("foo"));
        self::assertSame("foo", $list->get(0));
        self::assertSame("foo", $list[0]);

        self::assertSame("foo", $list->removeAt(0));
        self::assertTrue($list->add("foo"));

        self::assertCount(1, $list);
        unset($list[0]);
        self::assertCount(0, $list);
        self::assertTrue($list->isEmpty());
    }

    /**
     * @test
     * @covers \BradynPoulsen\Kotlin\Types\Internal\StringSerializer
     */
    public function toString_basicElement(): void
    {
        $list = new MutableArrayList(Types::integerOrNull());
        $list->add(10);
        $list->add(null);
        $list->add(1000);

        self::assertSame(
            "BradynPoulsen\Kotlin\Collections\Internal\MutableArrayList<?integer>[10, null, 1000]",
            (string)$list
        );
    }

    /**
     * @test
     */
    public function get_notContained(): void
    {
        $this->expectException(NoSuchElementException::class);

        $list = new MutableArrayList(Types::nothing());
        $list->get(0);
    }

    /**
     * @test
     */
    public function set_replaceItem(): void
    {
        $list = new MutableArrayList(Types::string());
        $list->add("foo");
        self::assertSame("foo", $list->set(0, "bar"));
        self::assertCount(1, $list);
    }

    /**
     * @test
     */
    public function set_notContained(): void
    {
        $this->expectException(NoSuchElementException::class);

        $list = new MutableArrayList(Types::boolean());
        $list->set(0, true);
    }

    /**
     * @test
     */
    public function addAll_addMultiple(): void
    {
        $source = listOf(Types::string(), ["foo", "bar"]);

        $target = new MutableArrayList(Types::stringOrNull());
        $target->add(null);
        $target->addAll($source);
        $target->add("baz");

        self::assertSame([null, "foo", "bar", "baz"], $target->toArray());
    }

    /**
     * @test
     */
    public function addAt_appendToEnd(): void
    {
        $target = new MutableArrayList(Types::string());
        $target->add("foo");
        $target->addAt(1, "bar");
        self::assertSame(["foo", "bar"], $target->toArray());
    }

    /**
     * @test
     */
    public function addAt_beforeBounds(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $target = new MutableArrayList(Types::string());
        $target->addAt(-1, "foo");
    }

    /**
     * @test
     */
    public function addAt_pastBounds(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $target = new MutableArrayList(Types::string());
        $target->addAt(1, "foo");
    }

    /**
     * @test
     */
    public function addAt_beginning(): void
    {
        $target = new MutableArrayList(Types::string());
        $target->add("bar");
        $target->addAt(0, "foo");
        self::assertSame(["foo", "bar"], $target->toArray());
    }

    /**
     * @test
     */
    public function addAt_insideMiddle(): void
    {
        $target = new MutableArrayList(Types::string());
        $target->add("foo");
        $target->add("baz");
        $target->addAt(1, "bar");
        self::assertSame(["foo", "bar", "baz"], $target->toArray());
    }

    /**
     * @test
     */
    public function addAllAt_beforeBounds(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $source = listOf(Types::string());
        $target = new MutableArrayList(Types::string());
        $target->addAllAt(-1, $source);
    }

    /**
     * @test
     */
    public function addAllAt_pastBounds(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $source = listOf(Types::string());
        $target = new MutableArrayList(Types::string());
        $target->addAllAt(1, $source);
    }

    /**
     * @test
     */
    public function addAllAt_beginning(): void
    {
        $source = listOf(Types::string(), ["foo", "bar"]);

        $target = new MutableArrayList(Types::string());
        $target->add("baz");
        $target->addAllAt(0, $source);
        self::assertSame(["foo", "bar", "baz"], $target->toArray());
    }

    /**
     * @test
     */
    public function addAllAt_middleIndex(): void
    {
        $source = listOf(Types::string(), ["bar", "baz"]);

        $target = new MutableArrayList(Types::string());
        $target->add("foo");
        $target->add("quaz");
        $target->addAllAt(1, $source);
        self::assertSame(["foo", "bar", "baz", "quaz"], $target->toArray());
    }

    /**
     * @test
     */
    public function addAllAt_appendToEnd(): void
    {
        $source = listOf(Types::string(), ["baz"]);

        $target = new MutableArrayList(Types::string());
        $target->add("foo");
        $target->add("bar");
        $target->addAllAt(2, $source);
        self::assertSame(["foo", "bar", "baz"], $target->toArray());
    }

    /**
     * @test
     */
    public function clear(): void
    {
        $source = new MutableArrayList(Types::string());
        $source->add("foo");
        $source->add("bar");
        $source->add("bar");
        $source->add("bar");
        $source->add("baz");

        self::assertCount(5, $source);
        $source->clear();
        self::assertEmpty($source);
    }

    /**
     * @test
     */
    public function remove_allIncludingDuplicates(): void
    {
        $source = new MutableArrayList(Types::string());
        $source->add("foo");
        $source->add("bar");
        $source->add("bar");
        $source->add("bar");
        $source->add("baz");

        self::assertTrue($source->remove("bar"));
        self::assertCount(2, $source);
    }

    /**
     * @test
     */
    public function removeAll_withNoop(): void
    {
        $emptyList = new ArrayList(Types::nothing());

        $source = new MutableArrayList(Types::string());
        $source->add("bar");

        $target = new MutableArrayList(Types::stringOrNull());
        $target->add(null);
        $target->add("foo");
        $target->add("bar");

        self::assertFalse($target->removeAll($emptyList));
        self::assertTrue($target->removeAll($source));
        self::assertFalse($target->removeAll($source));
    }

    /**
     * @test
     */
    public function retainAll_withNoop(): void
    {
        $emptyList = new ArrayList(Types::nothing());

        $source = new MutableArrayList(Types::string());
        $source->add("bar");

        $target = new MutableArrayList(Types::stringOrNull());
        $target->add(null);
        $target->add("foo");
        $target->add("bar");

        self::assertTrue($target->retainAll($source));
        self::assertCount(1, $target);
        self::assertFalse($target->retainAll($source));
        self::assertTrue($target->retainAll($emptyList));
        self::assertEmpty($target);
    }

    /**
     * @test
     */
    public function removeAt_notContained(): void
    {
        $this->expectException(NoSuchElementException::class);
        $list = new MutableArrayList(Types::nothing());
        $list->removeAt(0);
    }

    /**
     * @test
     */
    public function toList_noop(): void
    {
        $list = new ArrayList(Types::nothing());
        self::assertSame($list, $list->toList());
    }

    /**
     * @test
     */
    public function toList_removeMutability(): void
    {
        $list = new MutableArrayList(Types::string());
        $list->add(uniqid());
        $list->add(uniqid());
        $list->add(uniqid());

        $newList = $list->toList();
        self::assertNotSame($list, $newList);
        self::assertSame($list->toArray(), $newList->toArray());
    }

    /**
     * @test
     */
    public function toMutableList_noop(): void
    {
        $list = new MutableArrayList(Types::nothing());
        self::assertSame($list, $list->toMutableList());
    }

    /**
     * @test
     */
    public function toMutableList_addMutability(): void
    {
        $list = new ArrayList(Types::nothingOrNull());

        $newList = $list->toMutableList();
        $newList->add(null);

        self::assertNotSame($list, $newList);
        self::assertNotSame($list->toArray(), $newList->toArray());
    }

    /**
     * @test
     */
    public function toSet_removesDuplicates(): void
    {
        $list = new MutableArrayList(Types::string());
        $list->add("foo");
        $list->add("foo");
        $list->add("bar");
        $list->add("bar");
        $list->add("baz");
        $list->add("baz");

        self::assertSame(["foo", "bar", "baz"], $list->toSet()->toArray());
    }
}
