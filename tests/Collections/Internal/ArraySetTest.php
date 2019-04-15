<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use function BradynPoulsen\Kotlin\Collections\listOf;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\AbstractArrayCollection
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\ArraySet
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\ElementHashCalculator
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableArrayCollectionTrait
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableArraySet
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableSetTrait
 */
class ArraySetTest extends TestCase
{
    /**
     * @test
     */
    public function toSet_noop(): void
    {
        $set = new ArraySet(Types::nothing());
        self::assertSame($set, $set->toSet());
    }

    /**
     * @test
     */
    public function toSet_removeMutability(): void
    {
        $set = new MutableArraySet(Types::nothingOrNull());
        $set->add(null);

        $newSet = $set->toSet();
        self::assertNotSame($set, $newSet);
        self::assertSame([null], $newSet->toArray());
    }

    /**
     * @test
     */
    public function toMutableSet_noop(): void
    {
        $set = new MutableArraySet(Types::nothing());
        self::assertSame($set, $set->toMutableSet());
    }

    /**
     * @test
     */
    public function toMutableSet_addMutability(): void
    {
        $set = new ArraySet(Types::nothingOrNull());
        $newSet = $set->toMutableSet();
        self::assertNotSame($set, $newSet);
    }

    /**
     * @test
     */
    public function toMutableSet_noDuplicatesFromList(): void
    {
        $list = listOf(Types::string(), ["foo", "foo", "bar"]);
        self::assertSame(["foo", "bar"], $list->toMutableSet()->toArray());
    }

    /**
     * @test
     */
    public function add_rejectDuplicate(): void
    {
        $set = new MutableArraySet(Types::string());
        self::assertTrue($set->add("foo"));
        self::assertFalse($set->add("foo"));
    }

    /**
     * @test
     */
    public function addAll_withoutDuplicates(): void
    {
        $source = new MutableArraySet(Types::string());
        $source->add("foo");
        $source->add("baz");

        $target = new MutableArraySet(Types::stringOrNull());
        $target->add(null);
        $target->add("foo");
        $target->add("bar");

        self::assertTrue($target->addAll($source));
        self::assertSame([null, "foo", "bar", "baz"], $target->toArray());
    }
}
