<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use ArrayIterator;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\TypeCheckIterator
 */
class TypeCheckIteratorTest extends TestCase
{
    /**
     * @test
     */
    public function key_isSequential(): void
    {
        $iterator = new ArrayIterator([4, 3, 2, 1, 0]);

        $checkedIterator = new TypeCheckIterator(Types::integer(), $iterator);
        foreach ($checkedIterator as $key => $value) {
            self::assertEquals($value, 4 - $key);
        }

        self::assertTrue(Types::integer()->containsType($checkedIterator->getType()));
    }

    /**
     * @test
     */
    public function asIteration_returnsInOrder(): void
    {
        $iterator = new ArrayIterator([4, 3, 2, 1, 0]);

        $checkedIterator = new TypeCheckIterator(Types::integer(), $iterator);
        $iteration = $checkedIterator->asIteration();

        $values = [];
        while ($iteration->hasNext()) {
            array_push($values, $iteration->next());
        }

        self::assertEquals([4, 3, 2, 1, 0], $values);
    }
}
