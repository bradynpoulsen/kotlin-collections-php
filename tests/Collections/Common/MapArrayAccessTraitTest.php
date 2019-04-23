<?php
/** @noinspection PhpIllegalArrayKeyTypeInspection */
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Common;

use BradynPoulsen\Kotlin\Types\Types;
use BradynPoulsen\Kotlin\UnsupportedOperationException;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Collections\mapOf;
use function BradynPoulsen\Kotlin\Collections\mutableMapOf;
use function BradynPoulsen\Kotlin\pair;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Common\MapArrayAccessTrait
 * @covers \BradynPoulsen\Kotlin\Types\Types
 */
class MapArrayAccessTraitTest extends TestCase
{
    /**
     * @test
     */
    public function Map_accessWhenEmpty(): void
    {
        $map = mapOf(Types::nothing(), Types::nothing());
        self::assertFalse(isset($map["foo"]));
        self::assertNull($map["foo"]);
    }

    /**
     * @test
     */
    public function Map_accessWhenNotContained(): void
    {
        $map = mapOf(Types::string(), Types::integer(), [
            pair("foo", 100)
        ]);
        self::assertFalse(isset($map["bar"]));
        self::assertNull($map["bar"]);
    }

    /**
     * @test
     */
    public function Map_accessWhenContained(): void
    {
        $testComplexKey = [$this, __FUNCTION__];

        $map = mapOf(Types::callableOf(), Types::integer(), [
            pair($testComplexKey, 100)
        ]);
        self::assertTrue(isset($map[$testComplexKey]));
        self::assertSame(100, $map[$testComplexKey]);
    }

    /**
     * @test
     */
    public function Map_unsupported_offsetSet(): void
    {
        $this->expectException(UnsupportedOperationException::class);
        $testComplexKey = (object)[];
        $map = mapOf(Types::nothing(), Types::nothing());
        $map[$testComplexKey] = 100;
    }

    /**
     * @test
     */
    public function Map_unsupported_offsetUnset(): void
    {
        $this->expectException(UnsupportedOperationException::class);
        $testComplexKey = tmpfile();
        $map = mapOf(Types::nothing(), Types::nothing());
        try {
            unset($map[$testComplexKey]);
        } finally {
            fclose($testComplexKey);
        }
    }

    /**
     * @test
     */
    public function MutableMap_settingKey(): void
    {
        $testKey = ["foo", "bar", "baz"];
        $map = mutableMapOf(Types::arrayOf(), Types::string());
        self::assertNull($map[$testKey]);
        $map[$testKey] = uniqid("new_value");
        self::assertNotNull($map[$testKey]);
    }

    /**
     * @test
     */
    public function MutableMap_removingKey(): void
    {
        $map = mutableMapOf(Types::mixed(), Types::string(), [
            pair(1, 'integer'),
            pair(1.0, 'float'),
            pair(true, 'boolean')
        ]);

        self::assertSame('integer', $map[1]);
        // Strings are incorrectly casted when they look like an integer
        self::assertSame('integer', $map["1"]);

        self::assertSame('float', $map[1.0]);
        self::assertSame('boolean', $map[true]);

        unset($map[1]);
        self::assertNull($map[1]);
        self::assertNull($map["1"]);
        self::assertCount(2, $map);
        self::assertSame([1.0, true], $map->getKeys()->toArray());
    }
}
