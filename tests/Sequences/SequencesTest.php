<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences;

use ArrayIterator;
use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Types\Types;
use Generator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Sequences
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorSequence
 */
class SequencesTest extends TestCase
{
    /**
     * @test
     */
    public function iterator_acceptsGenerator(): void
    {
        $sequence = Sequences::iterator(Types::integer(), call_user_func(function (): Generator {
            yield 1;
            yield 2;
            yield 3;
        }));

        self::assertEquals([1, 2, 3], $sequence->toArray());
    }

    /**
     * @test
     */
    public function iterator_canBeChained(): void
    {
        $sequence = Sequences::iterator(Types::integer(), new ArrayIterator([1, 2, 3]))
            ->map(Types::integer(), function (int $n): int {
                return $n * 2;
            });

        self::assertEquals([2, 4, 6], $sequence->toArray());
    }

    /**
     * @test
     */
    public function iterator_constrainedOnce(): void
    {
        $sequence = Sequences::iterator(Types::integer(), new ArrayIterator([1, 2, 3]));
        $sequence->toArray();

        $this->expectException(InvalidStateException::class);
        $sequence->toArray();
    }

    /**
     * @test
     */
    public function iteratorAggregate_accessedIteratorMultipleTimes(): void
    {
        $aggregate = new class implements IteratorAggregate {
            public $accessed = 0;

            public function getIterator(): Generator
            {
                $this->accessed++;
                yield 1;
                yield 2;
                yield 3;
            }
        };
        $sequence = Sequences::iteratorAggregate(Types::integer(), $aggregate);

        self::assertEquals([1, 2, 3], $sequence->toArray());
        self::assertEquals([1, 2, 3], $sequence->toArray());
        self::assertEquals(2, $aggregate->accessed);
    }
}
