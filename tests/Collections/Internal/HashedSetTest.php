<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use Throwable;
use UnexpectedValueException;

/**
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\HashedSet
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableHashedSet
 * @covers \BradynPoulsen\Kotlin\Collections\Internal\MutableArrayCollectionTrait
 */
class HashedSetTest extends TestCase
{
    /**
     * @test
     */
    public function contains_checkBaseOnHash(): void
    {
        $set = new HashedSet(Types::instance(Throwable::class));
        self::assertFalse($set->contains(new NoSuchElementException()));
    }

    /**
     * @test
     */
    public function add_preventsDuplicates(): void
    {
        $set = new MutableHashedSet(Types::string());
        self::assertTrue($set->add("foo"));
        self::assertFalse($set->add("foo"));
    }

    /**
     * @test
     */
    public function remove_basedOnHash(): void
    {
        $set = new MutableHashedSet(Types::integerOrNull());
        $set->add(100);
        self::assertTrue($set->remove(100));
        self::assertFalse($set->remove(100));
    }

    /**
     * @test
     */
    public function addAll_withoutDuplicates(): void
    {
        $emptySource = new HashedSet(Types::instance(InvalidStateException::class));

        $source = new MutableHashedSet(Types::instanceOrNull(UnexpectedValueException::class));
        $source->add(new InvalidStateException());

        $target = new MutableHashedSet(Types::instanceOrNull(Throwable::class));
        self::assertFalse($target->addAll($emptySource));
        self::assertTrue($target->addAll($source));
        self::assertCount(1, $target);
    }
}
